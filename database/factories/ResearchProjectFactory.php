<?php

namespace Database\Factories;

use App\Models\ResearchProject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResearchProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ResearchProject::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraphs(3, true),
            'abstract' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(['Computer Science', 'Biology', 'Chemistry', 'Physics', 'Engineering']),
            'field_of_study' => $this->faker->word(),
            'keywords' => implode(', ', $this->faker->words(5)),
            'status' => $this->faker->randomElement(['pending', 'under_review', 'approved', 'rejected']),
            'view_count' => 0,
            'views_count' => 0,
            'downloads_count' => 0,
            'submission_date' => now(),
        ];
    }
}
