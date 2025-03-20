<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\DocumentAnalyzerContract;
use App\Enums\DocumentAnalysisStatus;
use App\Models\DocumentAnalysis;
use App\ValueObjects\DocumentMetadata;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * @phpstan-import-type WorkScopeSchema from DocumentMetadata
 * @phpstan-import-type BidItemSchema from DocumentMetadata
 */
final class ProcessDocumentForAnalysis implements ShouldQueue
{
    use Queueable;

    public int $timeout = 120;

    public function __construct(
        public readonly DocumentAnalysis $documentAnalysis,
    ) {}

    public function handle(DocumentAnalyzerContract $documentAnalyzer): void
    {
        $document = $this->documentAnalysis->document;

        // // Log::withContext([
        //     'document_analysis_id' => $this->documentAnalysis->id,
        //     'document_id' => $document->id,
        // ]);

        DB::transaction(function () use ($document, $documentAnalyzer): void {
            try {
                // // Log::info('Starting document analysis');

                $this->markAsInProgress();
                $documentPath = Storage::disk('local')->path($document->path);
                $pdfContent = $documentAnalyzer->parsePdfContent($documentPath);

                // Log::info('Document content parsed successfully');

                $this->updateDocumentWithParsedContent($pdfContent);
                $documentMetadata = $documentAnalyzer->analyzeDocument($pdfContent, $this->documentAnalysis);

                // Log::info('Document results received', [
                //     'metadata' => $documentMetadata,
                // ]);

                $this->updateDocumentAnalysis($documentMetadata);

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

    private function updateDocumentWithParsedContent(string $parsedContent): void
    {
        $this->documentAnalysis->update([
            'parsed_content' => $parsedContent,
        ]);
    }

    private function updateDocumentAnalysis(DocumentMetadata $documentMetadata): void
    {
        if ($documentMetadata->summary === 'Not a special provisions document.') {
            $this->documentAnalysis->update([
                'status' => DocumentAnalysisStatus::CANCELLED,
                'failure_reason' => 'The document does not appear to be a special provisions document.',
            ]);
        } else {
            $this->updateDocumentAnalysisResults($documentMetadata);
        }
    }

    private function updateDocumentAnalysisResults(DocumentMetadata $documentSummary): void
    {
        $this->documentAnalysis->update([
            'status' => DocumentAnalysisStatus::COMPLETED,
            'document_summary' => $documentSummary->summary,
            'contract_number' => $documentSummary->contractNumber,
            'project_id' => $documentSummary->projectId,
            'engineers_estimate' => $documentSummary->engineersEstimate,
            'bid_due_date' => $documentSummary->bidDueDate,
            'number_of_working_days' => $documentSummary->numberOfWorkingDays,
            'dbe_goal' => $documentSummary->dbeGoal,
            'dir_number' => $documentSummary->dirNumber,
            'job_location' => $documentSummary->jobLocation,
            'llm_response' => $documentSummary->llmResponse,
        ]);

        if ($documentSummary->bidItems !== null && $documentSummary->bidItems !== []) {
            $this->updateBidItems($documentSummary->bidItems);
        }

        if ($documentSummary->workScopes !== null && $documentSummary->workScopes !== []) {
            $this->updateWorkScopeAnalyses($documentSummary->workScopes);
        }
    }

    /**
     * @param  BidItemSchema[]  $bidItems
     */
    private function updateBidItems(array $bidItems): void
    {
        $mappedBidItems = array_map(fn (array $bidItem): array => [
            'document_analysis_id' => $this->documentAnalysis->id,
            'item_number' => $bidItem['item_number'] ?? '',
            'item_code' => $bidItem['item_code'] ?? '',
            'item_description' => $bidItem['item_description'] ?? '',
            'unit_of_measure' => $bidItem['unit_of_measure'] ?? '',
            'estimated_quantity' => $bidItem['estimated_quantity'] ?? '',
        ], $bidItems);

        $createdWorkScopeAnalyses = $this->documentAnalysis
            ->bidItems()
            ->createMany($mappedBidItems);

        $this->documentAnalysis
            ->bidItems()
            ->saveMany($createdWorkScopeAnalyses);
    }

    /**
     * @param  WorkScopeSchema[]  $workScopes
     */
    private function updateWorkScopeAnalyses(array $workScopes): void
    {
        $mappedWorkScopes = array_map(fn (array $workScope): array => [
            'scope' => $workScope['scope'],
            'summary' => $workScope['summary'],
        ], $workScopes);

        foreach ($mappedWorkScopes as $workScope) {
            $this->documentAnalysis
                ->workScopes
                ->firstWhere('name', $workScope['scope'])
                ?->update([
                    'analysis' => $workScope['summary'],
                ]);
        }
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
