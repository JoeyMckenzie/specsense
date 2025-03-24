<?php

declare(strict_types=1);

namespace App\Contracts\Actions;

interface GeneratesThumbnailAction
{
    public function handle(string $path, string $filename): string;
}
