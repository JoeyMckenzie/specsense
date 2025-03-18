<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Concerns\HasVerifiedUser;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

final class VerifyEmailController extends Controller
{
    use HasVerifiedUser;

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($this->verifiedUser()->hasVerifiedEmail()) {
            return redirect()->intended(route('documents.index', absolute: false).'?verified=1');
        }

        if ($this->verifiedUser()->markEmailAsVerified()) {
            /** @var MustVerifyEmail $user */
            $user = $request->user(); // @phpstan-ignore-line

            event(new Verified($user));
        }

        return redirect()->intended(route('documents.index', absolute: false).'?verified=1');
    }
}
