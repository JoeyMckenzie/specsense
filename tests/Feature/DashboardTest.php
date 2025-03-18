<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;

describe('Dashboard', function (): void {
    it('ensures guests are redirected to the login page', function (): void {
        $this->get('/documents')->assertRedirect('/login');
    });

    it('allows authenticated users to visit the dashboard', function (): void {
        $this->actingAs(User::factory()->create());

        $this->get('/documents')->assertOk();
    });
});
