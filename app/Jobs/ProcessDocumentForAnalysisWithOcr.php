<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\Services\OcrAnalyzerContract;
use App\Enums\DocumentAnalysisStatus;
use App\Models\DocumentAnalysis;
use App\ValueObjects\DocumentMetadata;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;
use Throwable;

/**
 * @phpstan-import-type WorkScopeSchema from DocumentMetadata
 * @phpstan-import-type BidItemSchema from DocumentMetadata
 */
final class ProcessDocumentForAnalysisWithOcr implements ShouldQueue
{
    use Queueable;

    public int $timeout = 360;

    public function __construct(
        public readonly DocumentAnalysis $documentAnalysis,

    ) {
        //
    }

    public function handle(OcrAnalyzerContract $ocrAnalyzer): void
    {
        $document = $this->documentAnalysis->document;

        // // Log::withContext([
        //     'document_analysis_id' => $this->documentAnalysis->id,
        //     'document_id' => $document->id,
        // ]);

        DB::transaction(function () use ($document, $ocrAnalyzer): void {
            try {
                // // Log::info('Starting document analysis');
                Storage::disk('local')->makeDirectory("pdfs/$document->id/");

                $this->markAsInProgress();
                $documentPath = Storage::disk('local')->path($document->path);
                $pdf = new Pdf($documentPath);
                $paths = $pdf->saveAllPages(Storage::disk('local')->path("pdfs/$document->id/"));

                /** @var string[] $contents */
                $contents = [];

                foreach ($paths as $path) {
                    $contents[] = $ocrAnalyzer->getImageText($path);
                }

                if (count($contents) > 0) {
                    $content = implode("\n\n", $contents);
                }

                // Log::info('Successfully processed document');
            } catch (Throwable $e) {
                $this->handleError($e);

                throw $e;
            }
        });
    }

    public function failed(?Throwable $exception = null): void
    {
        $this->handleError($exception ?? new Exception('Unknown error'));
    }

    private function markAsInProgress(): void
    {
        $this->documentAnalysis->update([
            'status' => DocumentAnalysisStatus::IN_PROGRESS->value,
        ]);
    }

    private function handleError(Throwable $e): void
    {
        // Log::error("Failed to process document {$this->documentAnalysis->id}", [
        //     'error' => $e->getMessage(),
        //     'trace' => $e->getTraceAsString(),
        // ]);

        $this->documentAnalysis->update([
            'status' => DocumentAnalysisStatus::FAILED->value,
            'failure_reason' => $e->getMessage(),
        ]);
    }
}
