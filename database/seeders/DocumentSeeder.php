<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

final class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::all()->each(function (User $user): void {
            Document::factory()
                ->count(fake()->numberBetween(2, 5))
                ->for($user)
                ->create();
        });
    }
}
