<?php

namespace Database\Factories;

use App\Helpers\Api\V1\CreateUrlPath;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UrlFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'path' => CreateUrlPath::getPath(rand(0, 9999)),
            'target' => $this->faker->url()
        ];
    }
}
