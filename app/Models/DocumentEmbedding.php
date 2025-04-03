<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\DocumentEmbeddingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Pgvector\Laravel\HasNeighbors;
use Pgvector\Laravel\Vector;

final class DocumentEmbedding extends Model
{
    /** @use HasFactory<DocumentEmbeddingFactory> */
    use HasFactory;

    use HasNeighbors;

    /**
     * @return BelongsTo<Document, $this>
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

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
