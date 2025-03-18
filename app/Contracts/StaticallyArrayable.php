<?php

declare(strict_types=1);

namespace App\Contracts;

interface StaticallyArrayable
{
    /**
     * @return string[]
     */
    public static function toArray(): array;
}
