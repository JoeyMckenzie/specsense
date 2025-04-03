<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\Services\LlmConnectorContract;
use App\Contracts\Services\OcrAnalyzerContract;
use App\Models\Document;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

final class GenerateDocumentEmbeddings implements ShouldQueue
{
    use Queueable;

    public int $timeout = 360;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly Document $document,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(LlmConnectorContract $llmConnector, OcrAnalyzerContract $ocrAnalyzer, Logger $logger): void
    {
        $logger->withContext([
            'document_id' => $this->document->id,
        ]);

        $logger->info('Starting document embeddings generation...');

        Storage::disk('local')->makeDirectory("documents/{$this->document->id}/chunks/");

        $documentPath = Storage::disk('local')->path($this->document->path);
        $pdf = new Pdf($documentPath);

        $logger->info('Saving document pages');

        $paths = $pdf->saveAllPages(Storage::disk('local')->path("documents/{$this->document->id}/chunks/"));

        $logger->info('Document pages saved', [
            'count' => count($paths),
        ]);

        foreach ($paths as $index => $path) {
            $logger->withContext([
                'page' => $index + 1,
                'path' => $path,
            ]);

            $logger->info('Creating text from image');

            $fileContents = $ocrAnalyzer->getImageText($path);

            $logger->info('Getting document content embeddings');

            $embeddings = $llmConnector->getEmbeddings($fileContents);

            $logger->info('Embeddings generated, creating document embedding record');

            $embedding = $this->document->embeddings()->create([
                'embedding' => $embeddings,
                'content' => $fileContents,
                'page' => $index + 1,
                'path' => $path,
            ]);

            $logger->info('Document embedding record created', [
                'document_embedding_id' => $embedding->id,
            ]);
        }
    }
}
