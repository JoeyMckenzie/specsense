<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Concerns\HasVerifiedUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class EmailVerificationPromptController extends Controller
{
    use HasVerifiedUser;

    /**
     * Show the email verification prompt page.
     */
    public function __invoke(Request $request): Response|RedirectResponse
    {
        return $this->verifiedUser()->hasVerifiedEmail()
            ? redirect()->intended(route('documents.index', absolute: false))
            : Inertia::render('auth/verify-email', ['status' => $request->session()->get('status')]);
    }
}
