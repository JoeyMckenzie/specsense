<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\WorkScopeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $name
 * @property string|null $analysis
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $document_analysis_id
 * @property-read DocumentAnalysis $documentAnalysis
 *
 * @method static \Database\Factories\WorkScopeFactory factory($count = null, $state = [])
 * @method static Builder<static>|WorkScope newModelQuery()
 * @method static Builder<static>|WorkScope newQuery()
 * @method static Builder<static>|WorkScope query()
 * @method static Builder<static>|WorkScope whereAnalysis($value)
 * @method static Builder<static>|WorkScope whereCreatedAt($value)
 * @method static Builder<static>|WorkScope whereDocumentAnalysisId($value)
 * @method static Builder<static>|WorkScope whereId($value)
 * @method static Builder<static>|WorkScope whereName($value)
 * @method static Builder<static>|WorkScope whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
final class WorkScope extends Model
{
    /** @use HasFactory<WorkScopeFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<DocumentAnalysis, $this>
     */
    public function documentAnalysis(): BelongsTo
    {
        return $this->belongsTo(DocumentAnalysis::class);
    }
}
