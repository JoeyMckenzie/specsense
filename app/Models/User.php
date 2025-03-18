<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin IdeHelperUser
 */
final class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'avatar',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var list<string>
     */
    protected $appends = [
        'full_name',
        'initials',
        'profile_image'
    ];

    /**
     * @return HasMany<Document, covariant $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * @return Attribute<string, ?string>
     */
    protected function profileImage(): Attribute
    {
        $avatar = $this->avatar !== null
            ? Storage::url($this->avatar)
            : null;

        /** @var Attribute<string, ?string> $profileImage */
        $profileImage = new Attribute(
            get: fn(): ?string => $avatar
        );


        return $profileImage;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return Attribute<string, string>
     */
    protected function fullName(): Attribute
    {
        /** @var Attribute<string, string> $fullName */
        $fullName = new Attribute(
            get: fn(): string => "$this->first_name $this->last_name"
        );

        return $fullName;
    }

    /**
     * @return Attribute<string, string>
     */
    protected function initials(): Attribute
    {
        $firstNameInitial = substr($this->first_name ?? '', 0, 1);
        $lastNameInitial = substr($this->last_name ?? '', 0, 1);

        /** @var Attribute<string, string> $initials */
        $initials = new Attribute(
            get: fn(): string => $firstNameInitial . $lastNameInitial
        );

        return $initials;
    }
}
