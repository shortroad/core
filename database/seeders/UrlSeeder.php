<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create user that has one url
        \App\Models\Url::factory(3)->create();

        // create user with multiple urls
        for ($i = 1; $i <= 5; $i++) {
            $user = \App\Models\User::factory()->create();
            \App\Models\Url::factory(rand(2, 10))->create(['user_id' => $user->id]);
        }
    }
}
