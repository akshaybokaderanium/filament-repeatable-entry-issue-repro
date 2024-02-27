<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConsentOption>
 */
class ConsentOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key'          => fake()->unique()->slug($title = fake()->title()),
            'version'      => 1,
            'title'        => $title,
            'sort_order'   => fake()->range(1, 5),
            'enabled'    => fake()->boolean(),
            'text'         => fake()->sentence(),
            'label'        => fake()->title(),
            'is_mandatory' => fake()->boolean(),
            'is_current'   => fake()->boolean(),
        ];
    }
}
