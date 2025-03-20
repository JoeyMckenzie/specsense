<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DocumentType;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

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
        $fs = Storage::fake('local');
        $filename = fake()->uuid().'.pdf';

        /** @var string $contents */
        $contents = file_get_contents(base_path('tests/Fixtures/Files/test_spec_list_1.pdf'));
        $fs->put($filename, $contents);

        return [
            'name' => fake()->sentence(3),
            'description' => fake()->optional()->sentence(),
            'original_filename' => fake()->word().'.pdf',
            'filename' => $filename,
            'path' => $filename,
            'size' => fake()->numberBetween(100000, 5000000), // Random size between 100KB and 5MB
            'type' => fake()->randomElement(DocumentType::cases()),
            'user_id' => User::factory(),
        ];
    }
}
