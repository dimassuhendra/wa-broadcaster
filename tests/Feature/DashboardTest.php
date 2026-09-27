<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Contact;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_workspace_sections_render_their_page_with_shared_layout(): void
    {
        $sections = [
            '/' => ['dashboard', 'Dashboard'],
            '/campaigns' => ['campaigns', 'WorkspaceSection'],
            '/contacts' => ['contacts', 'WorkspaceSection'],
            '/templates' => ['templates', 'WorkspaceSection'],
            '/analytics' => ['analytics', 'WorkspaceSection'],
            '/settings' => ['settings', 'WorkspaceSection'],
        ];

        foreach ($sections as $path => [$section, $component]) {
            $this->get($path)
                ->assertInertia(fn (Assert $page) => $page
                    ->component($component)
                    ->where('section', $section));
        }
    }

    public function test_dashboard_metrics_and_activity_are_calculated_from_database_records(): void
    {
        $this->travelTo(Carbon::parse('2026-09-27 12:00:00', 'Asia/Jakarta'));

        Contact::factory()->create(['created_at' => now()->subDay()]);
        Contact::factory()->create(['created_at' => now()->subMonth()->subDay()]);

        $recentCampaign = Campaign::factory()->sent()->create([
            'name' => 'Kampanye terbaru',
            'created_at' => now()->subDay(),
        ]);
        $previousCampaign = Campaign::factory()->sent()->create([
            'name' => 'Kampanye bulan lalu',
            'created_at' => now()->subMonth(),
        ]);
        Campaign::factory()->scheduled()->create([
            'created_at' => now()->subDays(5),
        ]);

        $repliedContact = Contact::factory()->create();
        $deliveredContact = Contact::factory()->create();
        $previousContact = Contact::factory()->create();

        $recentCampaign->recipients()->create([
            'contact_id' => $repliedContact->id,
            'status' => 'replied',
            'sent_at' => now()->subDay(),
            'delivered_at' => now()->subDay()->addMinute(),
            'read_at' => now()->subDay()->addMinutes(2),
            'replied_at' => now()->subDay()->addMinutes(3),
        ]);
        $recentCampaign->recipients()->create([
            'contact_id' => $deliveredContact->id,
            'status' => 'delivered',
            'sent_at' => now()->subDays(2),
            'delivered_at' => now()->subDays(2)->addMinute(),
        ]);
        $previousCampaign->recipients()->create([
            'contact_id' => $previousContact->id,
            'status' => 'replied',
            'sent_at' => now()->subMonth()->subDay(),
            'delivered_at' => now()->subMonth()->subDay()->addMinute(),
            'read_at' => now()->subMonth()->subDay()->addMinutes(2),
            'replied_at' => now()->subMonth()->subDay()->addMinutes(3),
        ]);

        $this->get('/')
            ->assertInertia(fn (Assert $page) => $page
                ->component('Dashboard')
                ->where('dashboard.stats.contacts.value', 5)
                ->where('dashboard.stats.contacts.change', 300)
                ->where('dashboard.stats.activeCampaigns', 1)
                ->where('dashboard.stats.sentMessages.value', 2)
                ->where('dashboard.stats.responseRate.value', 50)
                ->where('dashboard.stats.responseRate.change', -50)
                ->where('dashboard.weeklyMessages.total', 2)
                ->where('dashboard.campaignPerformance.sent', 2)
                ->where('dashboard.campaignPerformance.delivered', 2)
                ->where('dashboard.campaignPerformance.read', 1)
                ->where('dashboard.campaignPerformance.replied', 1)
                ->where('dashboard.recentCampaigns.0.name', 'Kampanye terbaru')
                ->where('dashboard.recentCampaigns.0.recipientCount', 2));
    }

    public function test_dashboard_shows_zero_metrics_when_the_database_has_no_activity(): void
    {
        $this->get('/')
            ->assertInertia(fn (Assert $page) => $page
                ->where('dashboard.stats.contacts.value', 0)
                ->where('dashboard.stats.activeCampaigns', 0)
                ->where('dashboard.stats.sentMessages.value', 0)
                ->where('dashboard.stats.responseRate.value', 0)
                ->where('dashboard.weeklyMessages.total', 0)
                ->has('dashboard.weeklyMessages.days', 7)
                ->where('dashboard.campaignPerformance.readRate', 0)
                ->has('dashboard.recentCampaigns', 0));
    }
}
