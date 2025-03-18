<?php

declare(strict_types=1);

namespace App\Http\Concerns;

use App\Exceptions\UserNotFoundOnRequestException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait HasVerifiedUser
{
    public function verifiedUser(): User
    {
        $user = Auth::user();

        if ($user === null) {
            throw new UserNotFoundOnRequestException;
        }

        return $user;
    }
}
