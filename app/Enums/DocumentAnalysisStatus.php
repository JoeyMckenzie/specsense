<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\StaticallyArrayable;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum DocumentAnalysisStatus: string implements StaticallyArrayable
{
    case NOT_STARTED = 'Not Started';

    case IN_PROGRESS = 'In Progress';

    case COMPLETED = 'Completed';

    case CANCELLED = 'Cancelled';

    case FAILED = 'Failed';

    public static function toArray(): array
    {
        /** @var string[] $statuses */
        $statuses = collect(self::cases())
            ->map(fn (DocumentAnalysisStatus $status) => $status->value)
            ->toArray();

        return $statuses;
    }

    public function getBadgeColor(): string
    {
        return match ($this) {
            self::NOT_STARTED => 'gray',
            self::IN_PROGRESS => 'info',
            self::CANCELLED => 'warning',
            self::COMPLETED => 'success',
            self::FAILED => 'danger',
        };
    }
}
