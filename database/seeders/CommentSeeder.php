<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Film;
use App\Models\Comment;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $films = Film::all();

        foreach ($films as $film) {
            $comments = Comment::factory(rand(1, 10))->create([
                'user_id' => fn() => $users->random()->id,
                'film_id' => $film->id,
            ]);

            foreach ($comments as $comment) {
                Comment::factory(rand(0, 3))->create([
                    'user_id' => fn() => $users->random()->id,
                    'film_id' => $film->id,
                    'rating' => rand(1, 10),
                    'comment_id' => $comment->id,
                ]);
            }
        }
    }
}
