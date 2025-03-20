<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

final class PromptNotFound extends Exception
{
    public function __construct(string $filePath)
    {
        parent::__construct("Prompt located at $filePath not found.");
    }
}
