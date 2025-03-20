<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DocumentAnalysis;
use App\Models\WorkScope;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkScope>
 */
final class WorkScopeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<WorkScope>
     */
    protected $model = WorkScope::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'document_analysis_id' => DocumentAnalysis::factory(),
        ];
    }
}
