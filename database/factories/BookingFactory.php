<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'customer' => $this->faker->name,
            'from' => $this->faker->dateTimeBetween('now', '+5 days')->format('Y-m-d'),
            'to' => $this->faker->dateTimeBetween('+6 days', '+10 days')->format('Y-m-d'),
            'price' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}