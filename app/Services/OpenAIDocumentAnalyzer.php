<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\DocumentAnalyzerContract;
use App\Contracts\LlmConnectorContract;
use App\Models\DocumentAnalysis;
use App\Models\WorkScope;
use App\Support\PromptParser;
use App\ValueObjects\DocumentMetadata;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Spatie\PdfToText\Pdf;

final readonly class OpenAIDocumentAnalyzer implements DocumentAnalyzerContract
{
    public function __construct(
        private LlmConnectorContract $connector
    ) {
        //
    }

    public function analyzeDocument(string $pdfContent, DocumentAnalysis $documentAnalysis): DocumentMetadata
    {
        $userPrompt = PromptParser::getPrompt(base_path('prompts/user.md'));

        if ($documentAnalysis->workScopes->count() > 0) {
            $scopesOfWork = $documentAnalysis->workScopes
                ->map(fn (WorkScope $workScope): string => "- $workScope->name")
                ->reduce(fn (string $acc, string $scope): string => sprintf("%s\n%s", $acc, $scope), '');
            $workScopesPrompt = <<<PROMPT
I would like you to provide summaries as well for the following scopes of work for the job:
{$scopesOfWork}

If a scope of work cannot be identified within the document, please use "Scope of work could not be identified." as the
summary in the response of work scope items. Please include the relevant sections of the document where information about
the scope of work can be found.

PROMPT;
            $userPrompt = Str::replace('##scopes_of_work##', $workScopesPrompt, $userPrompt);
        } else {
            $userPrompt = Str::replace('##scopes_of_work##', '', $userPrompt);
        }

        return $this->connector->getParsedDocumentMetadata($pdfContent, $userPrompt);
    }

    public function parsePdfContent(string $filePath): string
    {
        $pdfToTextPath = Config::string('app.pdf_to_text_path');

        return Pdf::getText($filePath, $pdfToTextPath);
    }
}
