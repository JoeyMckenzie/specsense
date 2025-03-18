<?php

declare(strict_types=1);

namespace App\Data\Documents;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
final class DashboardDocument extends Data {}
