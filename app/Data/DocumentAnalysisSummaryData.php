<?php

declare(strict_types=1);

namespace App\Data;

use App\Enums\DocumentAnalysisStatus;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class DocumentAnalysisSummaryData extends Data
{
    public DocumentAnalysisStatus $status;

    public ?string $documentSummary = null;

    public ?string $contractNumber = null;

    public ?string $projectId = null;

    public ?string $engineersEstimate = null;

    public ?string $bidDueDate = null;

    public ?string $numberOfWorkingDays = null;

    public ?string $dbeGoal = null;

    public ?string $dirNumber = null;

    public ?string $jobLocation = null;

    /**
     * @var WorkScopeSummaryData[]
     */
    public array $workScopes;

    /**
     * @var BidItemSummaryData[]
     */
    public array $bidItems;
}
