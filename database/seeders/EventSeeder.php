<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Faker\Factory;

class EventSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create();
        $users = User::all();

        // Creates 10 random events
        for ($i = 0; $i < 4; $i++) {
            $startDate = $faker->dateTimeBetween('now', '+1 year');
            $endDate = $faker->dateTimeBetween($startDate, $startDate->format('Y-m-d').' +10 days');
            
            Event::create([
                'title' => $faker->sentence(3),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'created_by' => $users->random()->id
            ]);
        }
    }
}
