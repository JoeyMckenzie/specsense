<?php

namespace App\Contracts\Actions;

use App\ValueObjects\DocumentMetadata;

interface ParsesDocumentContent
{
    public function handle(string $pdfContent, string $userPrompt): DocumentMetadata;
}