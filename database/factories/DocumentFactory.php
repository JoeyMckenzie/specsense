<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DocumentType;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
final class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filename = fake()->uuid().'.pdf';

        return [
            'name' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'original_filename' => fake()->word().'.pdf',
            'filename' => $filename,
            'path' => 'documents/'.$filename,
            'size' => fake()->numberBetween(100000, 5000000), // Random size between 100KB and 5MB
            'type' => fake()->randomElement(DocumentType::cases()),
            'user_id' => User::factory(),
        ];
    }
}
