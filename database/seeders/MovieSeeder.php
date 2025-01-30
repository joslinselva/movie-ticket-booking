<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() {
        $faker = Faker::create();

        $MoviesNameList = ['Movie 1'];
        $showDate = '2025-01-31'; // Static date
        $showTimes = ['09:00:00', '12:00:00', '15:00:00', '18:00:00', '22:00:00'];

        foreach ($MoviesNameList as $movie) {
            foreach ($showTimes as $time) {
                Movie::create([
                    'title' => $movie,
                    'show_date' => $showDate, 
                    'show_time' => $time,
                ]);
            }
        }
    }
}
