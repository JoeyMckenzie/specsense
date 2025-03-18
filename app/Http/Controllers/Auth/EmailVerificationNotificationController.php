<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Concerns\HasVerifiedUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class EmailVerificationNotificationController extends Controller
{
    use HasVerifiedUser;

    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($this->verifiedUser()->hasVerifiedEmail()) {
            return redirect()->intended(route('documents.index', absolute: false));
        }

        $this->verifiedUser()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
