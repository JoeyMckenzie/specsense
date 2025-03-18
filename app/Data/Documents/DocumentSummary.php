<?php

declare(strict_types=1);

namespace App\Data\Documents;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class DocumentSummary extends Data
{
    public int $id;

    public string $name;

    public string $description;

    public string $createdAt;

    public int $size;
}
