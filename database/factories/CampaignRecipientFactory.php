<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CampaignRecipient>
 */
class CampaignRecipientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'contact_id' => Contact::factory(),
            'status' => 'queued',
            'sent_at' => null,
            'delivered_at' => null,
            'read_at' => null,
            'replied_at' => null,
            'failed_at' => null,
            'error_message' => null,
        ];
    }

    public function sent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sent',
            'sent_at' => now()->subMinutes(fake()->numberBetween(2, 60)),
        ]);
    }

    public function delivered(): static
    {
        return $this->state(function (array $attributes): array {
            $sentAt = now()->subMinutes(fake()->numberBetween(30, 60));

            return [
                'status' => 'delivered',
                'sent_at' => $sentAt,
                'delivered_at' => $sentAt->copy()->addMinutes(fake()->numberBetween(1, 20)),
            ];
        });
    }

    public function read(): static
    {
        return $this->state(function (array $attributes): array {
            $sentAt = now()->subMinutes(fake()->numberBetween(45, 90));
            $deliveredAt = $sentAt->copy()->addMinutes(fake()->numberBetween(1, 20));

            return [
                'status' => 'read',
                'sent_at' => $sentAt,
                'delivered_at' => $deliveredAt,
                'read_at' => $deliveredAt->copy()->addMinutes(fake()->numberBetween(1, 20)),
            ];
        });
    }

    public function replied(): static
    {
        return $this->state(function (array $attributes): array {
            $sentAt = now()->subMinutes(fake()->numberBetween(60, 120));
            $deliveredAt = $sentAt->copy()->addMinutes(fake()->numberBetween(1, 20));
            $readAt = $deliveredAt->copy()->addMinutes(fake()->numberBetween(1, 20));

            return [
                'status' => 'replied',
                'sent_at' => $sentAt,
                'delivered_at' => $deliveredAt,
                'read_at' => $readAt,
                'replied_at' => $readAt->copy()->addMinutes(fake()->numberBetween(1, 20)),
            ];
        });
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'failed_at' => now()->subMinutes(fake()->numberBetween(1, 60)),
            'error_message' => fake()->sentence(),
        ]);
    }
}
