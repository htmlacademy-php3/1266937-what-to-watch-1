<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Film;
use App\Models\Genre;
use App\Models\Actor;
use App\Models\Director;

class FilmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $films = Film::factory(10)->create();

        $genres = Genre::all();
        $actors = Actor::all();
        $directors = Director::all();

        foreach ($films as $film) {
            $film->genres()->attach($genres->random(rand(1, 3)));
            $film->actors()->attach($actors->random(rand(3, 10)));
            $film->directors()->attach($directors->random(rand(1, 3)));
        }
    }
}
