<?php

declare(strict_types=1);

use App\Models\User;

describe('Authentication', function (): void {
    it('ensures login screen can be rendered', function (): void {
        $response = $this->get('/login');

        $response->assertStatus(200);
    });

    it('ensures users can authenticate using the login screen', function (): void {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('documents.index', absolute: false));
    });

    it('ensures users can not authenticate with invalid password', function (): void {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    });

    it('ensures users can logout', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    });
});
