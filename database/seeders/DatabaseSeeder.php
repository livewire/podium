<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(25)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::all()->each(function (User $user) {
            Question::factory(random_int(1, 5))->create([
                'user_id' => $user->id,
            ]);
        });

        User::all()->each(function (User $user) {
            Question::all()->random(10)->each(function (Question $question) use ($user) {
                $question->votes()->create([
                    'user_id' => $user->id,
                ]);
            });
        });
    }
}
