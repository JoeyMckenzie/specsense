<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\EmbeddingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\HasNeighbors;
use Pgvector\Laravel\Vector;

final class Embedding extends Model
{
    /** @use HasFactory<EmbeddingFactory> */
    use HasFactory;

    use HasNeighbors;

    /**
     * @return array<string, class-string|string>
     */
    protected function casts(): array
    {
        return [
            'embedding' => Vector::class,
        ];
    }
}
