<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'yudha',
            'email' => 'yudha@yudha.com',
            'password' => bcrypt('12345678')
        ]);
        User::factory(3)->create();
        Category::factory(3)->create();
        Post::factory(20)->create();
    }
}
