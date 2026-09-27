<?php

namespace Database\Factories;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message_template_id' => null,
            'name' => fake()->sentence(3),
            'message' => fake()->paragraph(),
            'status' => 'draft',
            'scheduled_at' => null,
            'started_at' => null,
            'finished_at' => null,
        ];
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
            'scheduled_at' => now()->addDays(fake()->numberBetween(1, 14)),
        ]);
    }

    public function sent(): static
    {
        return $this->state(function (array $attributes): array {
            $startedAt = now()->subDays(fake()->numberBetween(1, 30));

            return [
                'status' => 'sent',
                'scheduled_at' => null,
                'started_at' => $startedAt,
                'finished_at' => $startedAt->copy()->addMinutes(fake()->numberBetween(1, 30)),
            ];
        });
    }
}
