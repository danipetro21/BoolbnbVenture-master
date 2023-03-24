<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sponsorship>
 */
class SponsorshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cost' => fake() -> randomElement(['2.99' , '5.99' , '9.99']),
            'type' => fake() -> words(1, true),
            'duration' => fake() -> randomElement(['24', '72', '144']),
        ];
    }
}
