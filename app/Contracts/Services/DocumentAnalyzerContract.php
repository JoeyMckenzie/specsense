<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\Models\DocumentAnalysis;
use App\ValueObjects\DocumentMetadata;

interface DocumentAnalyzerContract
{
    public function parsePdfContent(string $filePath): string;

    public function analyzeDocument(string $pdfContent, DocumentAnalysis $documentAnalysis): DocumentMetadata;
}
