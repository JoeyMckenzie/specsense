<?php

declare(strict_types=1);

describe('Registration', function (): void {
    it('ensures registration screen can be rendered', function (): void {
        $response = $this->get('/register');

        $response->assertStatus(200);
    });

    it('ensures new users can register', function (): void {
        $response = $this->post('/register', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('documents.index', absolute: false));
    });
});
