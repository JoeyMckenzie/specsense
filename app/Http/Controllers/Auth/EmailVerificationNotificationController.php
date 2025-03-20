<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Concerns\HasVerifiedUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class EmailVerificationNotificationController
{
    use HasVerifiedUser;

    /**
     * Send a new email verification notification.
     */
    public function store(): RedirectResponse
    {
        if ($this->verifiedUser()->hasVerifiedEmail()) {
            return redirect()->intended(route('documents.index', absolute: false));
        }
        $this->verifiedUser()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    }
}
