<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Ecospace;
use App\Models\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        // Randomly associate a review with either an ecospace or an event
        $isEcospace = $this->faker->boolean(70);

        $ecospace = $isEcospace ? Ecospace::factory() : null;
        $event = $isEcospace ? null : Event::factory();

        $data = [
            'userID' => User::factory(),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'review' => $this->faker->paragraph(),
            'dateCreated' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'dateUpdated' => now(),
        ];

        // Only include `ecospaceID`/`eventID` keys if the physical table has the column.
        if (Schema::hasColumn('tbl_reviews', 'ecospaceID')) {
            $data['ecospaceID'] = $ecospace;
        }

        if (Schema::hasColumn('tbl_reviews', 'eventID')) {
            $data['eventID'] = $event;
        }

        return $data;
    }
}
