<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\User;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->text(),
            'image' => $this->faker->imageUrl(200, 200),
            'user_id' => $this->faker->randomElement(User::all()->modelKeys()),
            'category_id' => $this->faker->randomElement(Category::all()->modelKeys())
        ];
    }
}
