<?php

namespace Database\Factories;

use App\Models\Ecospace;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ecospace>
 */
class EcospaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ecospace::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ecospaceName' => $this->faker->company(),
            'ecospaceAdd' => $this->faker->address(),
            'ecospaceDesc' => $this->faker->paragraph(),
            'userID' => User::factory(),
            'statusID' => $this->faker->numberBetween(1, 3),
            'priceTierID' => $this->faker->numberBetween(1, 3),
            'openingHours' => '08:00',
            'closingHours' => '17:00',
            'daysOpened' => 'Mon,Tue,Wed,Thu,Fri',
        ];
    }
}
