<?php

declare(strict_types=1);

use App\Models\User;

describe('Password confirmation', function (): void {
    it('ensures confirm password screen can be rendered', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/confirm-password');

        $response->assertStatus(200);
    });

    it('ensures password can be confirmed', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    });

    it('ensures password is not confirmed with invalid password', function (): void {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    });
});
