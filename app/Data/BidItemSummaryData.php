<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class BidItemSummaryData extends Data
{
    public int $id;

    public ?string $itemNumber = null;

    public ?string $itemCode = null;

    public ?string $itemDescription = null;

    public ?string $unitOfMeasure = null;

    public ?string $estimatedQuantity = null;
}
