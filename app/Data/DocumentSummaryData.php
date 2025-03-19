<?php

declare(strict_types=1);

namespace App\Data;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class DocumentSummaryData extends Data
{
    public int $id;

    public string $name;

    public string $description;

    public string $createdAt;

    public int $size;

    public UserSummaryData $user;

    public ?string $thumbnail = null;
}
