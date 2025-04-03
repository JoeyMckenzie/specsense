<?php

declare(strict_types=1);

namespace App\Contracts\Services;

use App\ValueObjects\DocumentMetadata;

interface LlmConnectorContract
{
    /**
     * @return float[]
     */
    public function getEmbeddings(string $content): array;

    public function getParsedDocumentMetadata(string $pdfContent, string $userPrompt): DocumentMetadata;
}
