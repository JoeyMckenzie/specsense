<?php

declare(strict_types=1);

namespace App\Contracts\Services;

interface OcrAnalyzerContract
{
    public function analyzeDocument(string $filepath): string;
}
