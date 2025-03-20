<?php

declare(strict_types=1);

namespace App\Data;

use App\Enums\DocumentType;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class DocumentSummaryData extends Data
{
    public int $id;

    public string $name;

    public string $description;

    public CarbonImmutable $createdAt;

    public CarbonImmutable $updatedAt;

    public int $size;

    public UserSummaryData $user;

    public ?string $previewImage = null;

    public DocumentType $type;

    public ?DocumentAnalysisSummaryData $analysis = null;
}
