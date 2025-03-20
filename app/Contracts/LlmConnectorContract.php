<?php

declare(strict_types=1);

namespace App\Contracts;

use App\ValueObjects\DocumentMetadata;

interface LlmConnectorContract
{
    public function getParsedDocumentMetadata(string $pdfContent, string $userPrompt): DocumentMetadata;
}
