<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $document_id
 *
 * @method static \Database\Factories\DocumentAnalysisFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereBidDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereContractNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereDbeGoal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereDirNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereDocumentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereDocumentSummary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereEngineersEstimate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereFailureReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereJobLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereLlmResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereNumberOfWorkingDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereParsedContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DocumentAnalysis whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
final class DocumentAnalysis extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentAnalysisFactory> */
    use HasFactory;
}
