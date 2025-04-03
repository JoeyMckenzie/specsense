<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\Services\DocumentAnalyzerContract;
use App\Contracts\Services\LlmConnectorContract;
use App\Models\DocumentAnalysis;
use App\Models\WorkScope;
use App\Support\PromptParser;
use App\ValueObjects\DocumentMetadata;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Str;
use Spatie\PdfToText\Pdf;

final readonly class OpenAIDocumentAnalyzer implements DocumentAnalyzerContract
{
    public function __construct(
        private LlmConnectorContract $connector,
        private Repository $config
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
I would like you to provide detailed summaries as well for the following scopes of work for the job you are able to find within the special provisions document:
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
        /** @var string $pdfToTextPath */
        $pdfToTextPath = $this->config->get('app.pdf_to_text_path');

        return Pdf::getText($filePath, $pdfToTextPath);
    }
}
