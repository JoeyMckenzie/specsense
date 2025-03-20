<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\DocumentAnalysisFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string|null $context
 * @property string|null $parsed_content
 * @property string $status
 * @property string|null $document_summary
 * @property string|null $failure_reason
 * @property string|null $llm_response
 * @property string|null $contract_number
 * @property string|null $project_id
 * @property string|null $engineers_estimate
 * @property string|null $bid_due_date
 * @property string|null $number_of_working_days
 * @property string|null $dbe_goal
 * @property string|null $dir_number
 * @property string|null $job_location
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $document_id
 * @property-read Collection<int, BidItem> $bidItems
 * @property-read int|null $bid_items_count
 * @property-read Document $document
 * @property-read Collection<int, WorkScope> $workScopes
 * @property-read int|null $work_scopes_count
 *
 * @method static \Database\Factories\DocumentAnalysisFactory factory($count = null, $state = [])
 * @method static Builder<static>|DocumentAnalysis newModelQuery()
 * @method static Builder<static>|DocumentAnalysis newQuery()
 * @method static Builder<static>|DocumentAnalysis query()
 * @method static Builder<static>|DocumentAnalysis whereBidDueDate($value)
 * @method static Builder<static>|DocumentAnalysis whereContext($value)
 * @method static Builder<static>|DocumentAnalysis whereContractNumber($value)
 * @method static Builder<static>|DocumentAnalysis whereCreatedAt($value)
 * @method static Builder<static>|DocumentAnalysis whereDbeGoal($value)
 * @method static Builder<static>|DocumentAnalysis whereDirNumber($value)
 * @method static Builder<static>|DocumentAnalysis whereDocumentId($value)
 * @method static Builder<static>|DocumentAnalysis whereDocumentSummary($value)
 * @method static Builder<static>|DocumentAnalysis whereEngineersEstimate($value)
 * @method static Builder<static>|DocumentAnalysis whereFailureReason($value)
 * @method static Builder<static>|DocumentAnalysis whereId($value)
 * @method static Builder<static>|DocumentAnalysis whereJobLocation($value)
 * @method static Builder<static>|DocumentAnalysis whereLlmResponse($value)
 * @method static Builder<static>|DocumentAnalysis whereNumberOfWorkingDays($value)
 * @method static Builder<static>|DocumentAnalysis whereParsedContent($value)
 * @method static Builder<static>|DocumentAnalysis whereProjectId($value)
 * @method static Builder<static>|DocumentAnalysis whereStatus($value)
 * @method static Builder<static>|DocumentAnalysis whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
final class DocumentAnalysis extends Model
{
    /** @use HasFactory<DocumentAnalysisFactory> */
    use HasFactory;

    /**
     * @return HasMany<WorkScope, $this>
     */
    public function workScopes(): HasMany
    {
        return $this->hasMany(WorkScope::class);
    }

    /**
     * @return HasMany<BidItem, $this>
     */
    public function bidItems(): HasMany
    {
        return $this->hasMany(BidItem::class);
    }

    /**
     * @return BelongsTo<Document, $this>
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
