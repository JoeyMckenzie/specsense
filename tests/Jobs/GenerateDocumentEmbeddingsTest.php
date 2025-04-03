<?php

declare(strict_types=1);

use App\Enums\DocumentAnalysisStatus;
use App\Jobs\ExtractJobDetailsFromDocument;
use App\Jobs\GenerateDocumentEmbeddings;
use App\Models\Document;
use App\Models\User;
use App\Services\OpenAIConnector;
use App\Services\TesseractAnalyzer;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Queue;

describe(ExtractJobDetailsFromDocument::class, function (): void {
    it('updates document analysis status to completed when successful', function (): void {
        // Arrange
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();
        $logger = mock(Logger::class);

        Queue::fake();

        $logger->shouldReceive('withContext')
            ->times(1)
            ->withArgs(fn ($context): bool => $context['document_id'] === $document->id);

        $logger->shouldReceive('withContext')
            ->times(2)
            ->withAnyArgs();

        $logger->shouldReceive('info')
            ->times(7)
            ->withArgs(fn ($message): bool => in_array($message, [
                'Starting document embeddings generation...',
                'Saving document pages',
                'Document pages saved',
                'Creating text from image',
                'Getting document content embeddings',
                'Embeddings generated, creating document embedding record',
                'Document embedding record created',
            ]));

        // Act
        GenerateDocumentEmbeddings::dispatch($document);
        Queue::assertPushed(GenerateDocumentEmbeddings::class, fn (GenerateDocumentEmbeddings $job): bool => $job->document->id === $document->id);

        $job = new GenerateDocumentEmbeddings($document);
        $job->handle(new OpenAIConnector, new TesseractAnalyzer, $logger);

        // Assert status was updated
        expect($this->documentAnalysis->refresh())
            ->status->toBe(DocumentAnalysisStatus::COMPLETED->value);
    });
});
