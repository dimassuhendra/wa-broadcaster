<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Models\Contact;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(string $section = 'dashboard'): Response
    {
        $props = [
            'section' => $section,
        ];

        if ($section === 'dashboard') {
            $props['dashboard'] = $this->dashboardData();
        }

        $component = $section === 'dashboard' ? 'Dashboard' : 'WorkspaceSection';

        return Inertia::render($component, $props);
    }

    /**
     * @return array{
     *     stats: array{
     *         contacts: array{value: int, change: ?int},
     *         activeCampaigns: int,
     *         sentMessages: array{value: int, change: ?int},
     *         responseRate: array{value: float, change: ?float}
     *     },
     *     weeklyMessages: array{
     *         total: int,
     *         days: array<int, array{label: string, total: int, height: int, isToday: bool}>
     *     },
     *     campaignPerformance: array{
     *         sent: int,
     *         delivered: int,
     *         read: int,
     *         replied: int,
     *         readRate: float,
     *         responseRate: float
     *     },
     *     recentCampaigns: array<int, array{
     *         id: int,
     *         name: string,
     *         status: string,
     *         recipientCount: int,
     *         time: string
     *     }>
     * }
     */
    private function dashboardData(): array
    {
        $now = now();
        $currentMonthStart = $now->copy()->startOfMonth();
        $currentMonthEnd = $now->copy()->endOfMonth();
        $previousMonthStart = $currentMonthStart->copy()->subMonth()->startOfMonth();
        $previousMonthEnd = $previousMonthStart->copy()->endOfMonth();

        $newContactsThisMonth = Contact::query()
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();
        $newContactsLastMonth = Contact::query()
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->count();

        $currentMonthMessages = $this->messageCountsBetween(
            $currentMonthStart,
            $currentMonthEnd,
        );
        $previousMonthMessages = $this->messageCountsBetween(
            $previousMonthStart,
            $previousMonthEnd,
        );

        $currentResponseRate = $this->rate(
            $currentMonthMessages['replied'],
            $currentMonthMessages['sent'],
        );
        $previousResponseRate = $this->rate(
            $previousMonthMessages['replied'],
            $previousMonthMessages['sent'],
        );

        return [
            'stats' => [
                'contacts' => [
                    'value' => Contact::query()->count(),
                    'change' => $this->percentageChange($newContactsThisMonth, $newContactsLastMonth),
                ],
                'activeCampaigns' => Campaign::query()
                    ->whereIn('status', ['scheduled', 'sending'])
                    ->count(),
                'sentMessages' => [
                    'value' => $currentMonthMessages['sent'],
                    'change' => $this->percentageChange(
                        $currentMonthMessages['sent'],
                        $previousMonthMessages['sent'],
                    ),
                ],
                'responseRate' => [
                    'value' => $currentResponseRate,
                    'change' => round($currentResponseRate - $previousResponseRate, 1),
                ],
            ],
            'weeklyMessages' => $this->weeklyMessages(),
            'campaignPerformance' => $this->campaignPerformance($currentMonthMessages),
            'recentCampaigns' => Campaign::query()
                ->withCount('recipients')
                ->latest()
                ->limit(4)
                ->get()
                ->map(fn (Campaign $campaign): array => [
                    'id' => $campaign->id,
                    'name' => $campaign->name,
                    'status' => $campaign->status,
                    'recipientCount' => $campaign->recipients_count,
                    'time' => ($campaign->scheduled_at ?? $campaign->created_at)
                        ->locale('id')
                        ->translatedFormat('d M, H:i'),
                ])
                ->all(),
        ];
    }

    /**
     * @return array{total: int, days: array<int, array{label: string, total: int, height: int, isToday: bool}>}
     */
    private function weeklyMessages(): array
    {
        $today = now()->startOfDay();
        $periodStart = $today->copy()->subDays(6);
        $periodEnd = $today->copy()->endOfDay();

        $dailyTotals = CampaignRecipient::query()
            ->whereNotNull('sent_at')
            ->whereBetween('sent_at', [$periodStart, $periodEnd])
            ->selectRaw('DATE(sent_at) as sent_day, COUNT(*) as total')
            ->groupBy('sent_day')
            ->pluck('total', 'sent_day');

        $days = collect(range(0, 6))
            ->map(function (int $offset) use ($dailyTotals, $periodStart): array {
                $date = $periodStart->copy()->addDays($offset);
                $total = (int) $dailyTotals->get($date->toDateString(), 0);

                return [
                    'label' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'][$date->dayOfWeekIso - 1],
                    'total' => $total,
                    'height' => 0,
                    'isToday' => $date->isToday(),
                ];
            });

        $maximum = max(0, (int) $days->max('total'));

        $days = $days->map(function (array $day) use ($maximum): array {
            $day['height'] = $maximum > 0
                ? max(5, (int) round($day['total'] / $maximum * 90))
                : 0;

            return $day;
        });

        return [
            'total' => (int) $days->sum('total'),
            'days' => $days->all(),
        ];
    }

    /**
     * @return array{sent: int, delivered: int, read: int, replied: int}
     */
    private function messageCountsBetween(Carbon $start, Carbon $end): array
    {
        $counts = CampaignRecipient::query()
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN sent_at BETWEEN ? AND ? THEN 1 ELSE 0 END), 0) AS sent_count,
                COALESCE(SUM(CASE WHEN delivered_at BETWEEN ? AND ? THEN 1 ELSE 0 END), 0) AS delivered_count,
                COALESCE(SUM(CASE WHEN read_at BETWEEN ? AND ? THEN 1 ELSE 0 END), 0) AS read_count,
                COALESCE(SUM(CASE WHEN replied_at BETWEEN ? AND ? THEN 1 ELSE 0 END), 0) AS replied_count',
                [$start, $end, $start, $end, $start, $end, $start, $end],
            )
            ->first();

        return [
            'sent' => (int) $counts->sent_count,
            'delivered' => (int) $counts->delivered_count,
            'read' => (int) $counts->read_count,
            'replied' => (int) $counts->replied_count,
        ];
    }

    /**
     * @param  array{sent: int, delivered: int, read: int, replied: int}  $counts
     * @return array{sent: int, delivered: int, read: int, replied: int, readRate: float, responseRate: float}
     */
    private function campaignPerformance(array $counts): array
    {
        return [
            ...$counts,
            'readRate' => $this->rate($counts['read'], $counts['sent']),
            'responseRate' => $this->rate($counts['replied'], $counts['sent']),
        ];
    }

    private function rate(int $count, int $total): float
    {
        return $total > 0 ? round($count / $total * 100, 1) : 0;
    }

    private function percentageChange(int $current, int $previous): ?int
    {
        if ($previous === 0) {
            return $current === 0 ? 0 : null;
        }

        return (int) round(($current - $previous) / $previous * 100);
    }
}
