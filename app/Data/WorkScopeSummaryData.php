<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class WorkScopeSummaryData extends Data
{
    public int $id;

    public string $name;

    public ?string $analysis = null;
}
