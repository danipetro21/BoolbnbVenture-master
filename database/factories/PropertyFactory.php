<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title'  => fake() -> words(3, true),
            'description' => fake() -> paragraph(5, false),
            'room_number' => fake() -> numberBetween(1, 15),
            'bath_number' => fake() -> numberBetween(1,7),
            'mq2' => fake() -> numberBetween(50,500),
            'address'=> fake() -> streetAddress(),
            'lat' => fake() -> latitude($min = -90, $max = 90),
            'lon' => fake() -> longitude($min = -180, $max = 180),
            'visible' => fake() -> boolean(),
            'price_per_night' => fake() -> numberBetween(50,350),
            'bed_number' => fake() -> numberBetween(1,10),
        ];
    }
}