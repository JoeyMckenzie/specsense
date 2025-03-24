<?php

declare(strict_types=1);

namespace Tests\Jobs;

use App\Contracts\Actions\GeneratesThumbnailAction;
use App\Jobs\GenerateDocumentThumbnail;
use App\Models\Document;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery;

covers(GenerateDocumentThumbnail::class);

describe(GenerateDocumentThumbnail::class, function (): void {
    beforeEach(function (): void {
        Storage::fake('local');
        Storage::fake('public');
    });

    it('generates a thumbnail for a document', function (): void {
        // Arrange
        $path = 'documents/test-file.pdf';
        $filename = 'test-file.pdf';
        $document = Document::factory()->create([
            'path' => $path,
            'filename' => $filename,
            'thumbnail' => null,
        ]);

        $actionMock = Mockery::mock(GeneratesThumbnailAction::class);
        $actionMock->shouldReceive('handle')
            ->once()
            ->with($path, $filename)
            ->andReturn('thumbnails/test-file_thumb.jpg');

        // Act
        new GenerateDocumentThumbnail($document)->handle($actionMock);

        $document->refresh();

        // Assert
        expect($document->thumbnail)->toBe('thumbnails/test-file_thumb.jpg');
    });

    it('logs an error when thumbnail generation fails', function (): void {
        // Arrange
        $document = Document::factory()->create();
        $exception = new Exception('Something went wrong');

        Log::shouldReceive('error')
            ->once()
            ->with('Failed to generate thumbnail: Something went wrong');

        // Act
        new GenerateDocumentThumbnail($document)->fail($exception);
    });

    it('logs a default message when exception is null', function (): void {
        // Arrange
        $document = Document::factory()->create();

        Log::shouldReceive('error')
            ->once()
            ->with('Failed to generate thumbnail: Unknown error');

        // Act & Act
        new GenerateDocumentThumbnail($document)->fail();
    });
});
