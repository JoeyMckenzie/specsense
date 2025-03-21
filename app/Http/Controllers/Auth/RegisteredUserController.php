<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

final class RegisteredUserController
{
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(): RedirectResponse
    {
        return redirect()
            ->back()
            ->with('warning', 'Registration is currently disabled.');
    }

    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/register');
    }
}
