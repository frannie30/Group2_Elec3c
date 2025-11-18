<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'eventName' => $this->faker->sentence(3),
            'eventTypeID' => $this->faker->numberBetween(1, 5),
            'userID' => User::factory(),
            'eventAdd' => $this->faker->address(),
            'statusID' => $this->faker->numberBetween(1, 3),
            'priceTierID' => $this->faker->numberBetween(1, 3),
            'eventDate' => $this->faker->dateTimeBetween('-1 years', '+1 years')->format('Y-m-d'),
            'eventDesc' => $this->faker->paragraph(),
            'isDone' => $this->faker->boolean(20),
            'isFinished' => $this->faker->boolean(10),
        ];
    }
}
