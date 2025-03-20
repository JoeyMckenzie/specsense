<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BidItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string|null $item_number
 * @property string|null $item_code
 * @property string|null $item_description
 * @property string|null $unit_of_measure
 * @property string|null $estimated_quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $document_analysis_id
 * @property-read DocumentAnalysis|null $analysis
 *
 * @method static \Database\Factories\BidItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BidItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BidItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BidItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BidItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BidItem whereDocumentAnalysisId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BidItem whereEstimatedQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BidItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BidItem whereItemCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BidItem whereItemDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BidItem whereItemNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BidItem whereUnitOfMeasure($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BidItem whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class BidItem extends Model
{
    /** @use HasFactory<BidItemFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<DocumentAnalysis, $this>
     */
    public function analysis(): BelongsTo
    {
        return $this->belongsTo(DocumentAnalysis::class);
    }
}
