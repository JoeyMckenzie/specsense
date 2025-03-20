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
}
