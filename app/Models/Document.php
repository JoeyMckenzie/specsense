<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\DocumentFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property string|null $description
 * @property string $name
 * @property string $original_filename
 * @property string $filename
 * @property string $path
 * @property string|null $thumbnail
 * @property int $size
 * @property string $type
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $user_id
 * @property-read DocumentAnalysis|null $analysis
 * @property-read string|null $preview_image
 * @property-read User $user
 *
 * @method static DocumentFactory factory($count = null, $state = [])
 * @method static Builder<static>|Document newModelQuery()
 * @method static Builder<static>|Document newQuery()
 * @method static Builder<static>|Document query()
 * @method static Builder<static>|Document whereCreatedAt($value)
 * @method static Builder<static>|Document whereDescription($value)
 * @method static Builder<static>|Document whereFilename($value)
 * @method static Builder<static>|Document whereId($value)
 * @method static Builder<static>|Document whereName($value)
 * @method static Builder<static>|Document whereOriginalFilename($value)
 * @method static Builder<static>|Document wherePath($value)
 * @method static Builder<static>|Document whereSize($value)
 * @method static Builder<static>|Document whereThumbnail($value)
 * @method static Builder<static>|Document whereType($value)
 * @method static Builder<static>|Document whereUpdatedAt($value)
 * @method static Builder<static>|Document whereUserId($value)
 *
 * @mixin Eloquent
 */
final class Document extends Model
{
    /** @use HasFactory<DocumentFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $appends = [
        'preview_image',
    ];

    /**
     * @return BelongsTo<User, covariant $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne<DocumentAnalysis, $this>
     */
    public function analysis(): HasOne
    {
        return $this->hasOne(DocumentAnalysis::class);
    }

    /**
     * @return HasMany<DocumentEmbedding, $this>
     */
    public function embeddings(): HasMany
    {
        return $this->hasMany(DocumentEmbedding::class);
    }

    /**
     * @return Attribute<string, string>
     */
    protected function previewImage(): Attribute
    {
        $thumbnail = $this->thumbnail !== null
            ? Storage::url($this->thumbnail)
            : null;

        return Attribute::make(fn (): ?string => $thumbnail);
    }
}
