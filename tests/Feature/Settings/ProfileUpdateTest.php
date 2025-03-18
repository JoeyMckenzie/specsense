<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('Profile updates', function (): void {
    it('should ensure the profile page is displayed', function (): void {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/settings/profile');

        $response->assertOk();
    });

    it('ensures the profile information can be updated', function (): void {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/settings/profile', [
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings/profile');

        $user->refresh();

        expect($user->full_name)->toBe('Test User');
        expect($user->email)->toBe('test@example.com');
        expect($user->email_verified_at)->toBeNull();
    });

    it('ensures email verification status is unchanged when the email address is unchanged', function (): void {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/settings/profile', [
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings/profile');

        expect($user->refresh()->email_verified_at)->not->toBeNull();
    });

    it('ensures user can delete their account', function (): void {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/settings/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        expect($this->isAuthenticated())->toBeFalse();
        expect($user->fresh())->toBeNull();
    });

    it('ensures correct password must be provided to delete account', function (): void {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/settings/profile')
            ->delete('/settings/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrors('password')
            ->assertRedirect('/settings/profile');

        expect($user->fresh())->not->toBeNull();
    });

    it('ensures profile photo can be uploaded', function (): void {
        $user = User::factory()->create();

        Storage::fake('public');

        $response = $this
            ->actingAs($user)
            ->patch('/settings/profile', [
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => $user->email,
                'profile_image' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/settings/profile');

        $user->refresh();

        expect($user->avatar)->not->toBeNull();
        expect(Storage::disk('public')->exists($user->avatar))->toBeTrue();
    });

    it('ensures profile photo can be removed', function (): void {
        $user = User::factory()->create();

        Storage::fake('public');

        $response = $this->actingAs($user)->patch('/settings/profile', [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $user->email,
            'profile_image' => UploadedFile::fake()->image('photo.jpg'),
        ]);

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/settings/profile');

        $user->refresh();
        $this->assertNotNull($user->avatar);
        $this->assertTrue(Storage::disk('public')->exists($user->avatar));

        $oldPath = $user->avatar;

        $response = $this->actingAs($user)->delete('/settings/profile-photo');

        $response->assertSessionHasNoErrors()
            ->assertRedirect('/settings/profile');

        $user->refresh();
        $this->assertNull($user->avatar);
        $this->assertFalse(Storage::disk('public')->exists($oldPath));
    });
});
