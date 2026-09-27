<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => '+628'.fake()->unique()->numerify('#########'),
            'email' => fake()->optional()->safeEmail(),
            'company' => fake()->optional()->company(),
            'notes' => fake()->optional()->sentence(),
            'is_whatsapp_opt_in' => true,
            'consented_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'opted_out_at' => null,
            'opt_in_source' => fake()->randomElement(['website', 'checkout', 'in_store', 'event']),
        ];
    }

    public function withoutWhatsAppConsent(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_whatsapp_opt_in' => false,
            'consented_at' => null,
            'opted_out_at' => null,
            'opt_in_source' => null,
        ]);
    }

    public function optedOut(): static
    {
        return $this->state(function (array $attributes): array {
            $optedOutAt = now()->subDays(fake()->numberBetween(1, 90));

            return [
                'is_whatsapp_opt_in' => false,
                'consented_at' => $optedOutAt->copy()->subDays(fake()->numberBetween(1, 90)),
                'opted_out_at' => $optedOutAt,
                'opt_in_source' => 'website',
            ];
        });
    }
}
