<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\StaticallyArrayable;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum DocumentType: string implements StaticallyArrayable
{
    case SPECIAL_PROVISIONS = 'Special Provisions';

    case PLAN_DOCUMENT = 'Plan Document';

    case OTHER = 'Other';

    public static function toArray(): array
    {
        /** @var string[] $documents */
        $documents = collect(self::cases())
            ->map(fn (DocumentType $type) => $type->value)
            ->toArray();

        return $documents;
    }
}
