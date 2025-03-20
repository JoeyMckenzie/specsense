<?php

declare(strict_types=1);

namespace App\Support;

use App\Exceptions\PromptNotFound;

final class PromptParser
{
    public static function getPrompt(string $filePath): string
    {
        $contents = file_get_contents($filePath);

        if ($contents === false) {
            throw new PromptNotFound($filePath);
        }

        return $contents;
    }
}
