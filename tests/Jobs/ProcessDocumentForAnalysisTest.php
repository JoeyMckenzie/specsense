<?php

declare(strict_types=1);

use App\Enums\DocumentAnalysisStatus;
use App\Jobs\ProcessDocumentForAnalysis;
use App\Models\Document;
use App\Models\DocumentAnalysis;
use App\Models\User;
use App\Models\WorkScope;
use App\Services\OpenAIConnector;
use App\Services\OpenAIDocumentAnalyzer;
use Illuminate\Support\Facades\Queue;

// covers(ProcessDocumentForAnalysis::class);

describe(ProcessDocumentForAnalysis::class, function (): void {
    beforeEach(function (): void {
        // Setup data
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();
        $this->documentAnalysis = DocumentAnalysis::factory()->for($document)->create([
            'status' => DocumentAnalysisStatus::NOT_STARTED,
        ]);

        WorkScope::factory()->for($this->documentAnalysis)->createMany([
            [
                'name' => 'trenching',
            ],
            [
                'name' => 'asphalt concrete',
            ],
        ]);

        // Setup facades
        Queue::fake();
        // Log::shouldReceive('withContext')
        //     ->once()
        //     ->with([
        //         'document_analysis_id' => $this->documentAnalysis->id,
        //         'document_id' => $this->documentAnalysis->document?->id,
        //     ])
        //     ->andReturn(Log::class);
    });

    it('updates document analysis status to completed when successful', function (): void {
        // Arrange
        // Log::shouldReceive('info')
        //     ->times(9) // Adjust based on number of log calls in your job
        //     ->withArgs(fn ($message): bool => in_array($message, [
        //         'Starting document analysis',
        //         'Parsing document content into text chunks',
        //         'Confidence score calculated',
        //         'Skipping document analysis for document, confidence score is below threshold',
        //         'Chunking document content',
        //         'Chunking complete, processing chunks',
        //         'Processing chunk',
        //         'Chunk processed successfully',
        //         'Analysis complete, updating record with final summary',
        //         'Successfully processed document',
        //     ]));

        // Log::shouldReceive('warning')
        //     ->times(1)
        //     ->withArgs(fn ($message): bool => $message === 'Skipping document analysis for document, confidence score is below threshold');

        // Act
        ProcessDocumentForAnalysis::dispatch($this->documentAnalysis);

        // Assert job was dispatched
        Queue::assertPushed(ProcessDocumentForAnalysis::class, fn (ProcessDocumentForAnalysis $job): bool => $job->documentAnalysis->id === $this->documentAnalysis->id);

        // Test actual job execution
        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);
        $job->handle(new OpenAIDocumentAnalyzer(new OpenAIConnector));
        // $job->handle(new GeminiDocumentAnalyzer(new GeminiConnector));

        // Assert status was updated
        expect($this->documentAnalysis->refresh())
            ->status->toBe(DocumentAnalysisStatus::COMPLETED->value);
    });

    it('updates document analysis status to failed when job fails', function (): void {
        // Arrange
        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);

        // Act
        $job->failed();

        // Assert
        $updatedAnalysis = $this->documentAnalysis->refresh();
        expect($updatedAnalysis)->toBeInstanceOf(DocumentAnalysis::class)
            ->analysis_status->toBe(DocumentAnalysisStatus::FAILED->value);
    });
})->skip();
