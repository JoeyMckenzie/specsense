<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Concerns\HasVerifiedUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

final class ProfilePhotoController extends Controller
{
    use HasVerifiedUser;

    /**
     * Delete the current user's profile photo.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $avatarPath = $this->verifiedUser()->avatar;

        if ($avatarPath !== null && Storage::disk('public')->exists($avatarPath)) {
            Storage::disk('public')->delete($avatarPath);
        }

        $this->verifiedUser()->avatar = null;
        $this->verifiedUser()->save();

        return to_route('profile.edit');
    }
}
