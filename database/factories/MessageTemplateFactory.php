<?php

namespace Database\Factories;

use App\Models\MessageTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MessageTemplate>
 */
class MessageTemplateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'category' => fake()->randomElement(['promosi', 'pengingat', 'layanan pelanggan', 'pembaruan']),
            'language' => 'id',
            'body' => fake()->paragraph(),
            'status' => 'draft',
            'external_template_id' => null,
            'variables' => [fake()->word()],
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }
}
