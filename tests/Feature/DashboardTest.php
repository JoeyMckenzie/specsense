<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;

describe('Dashboard', function () {
    it('ensures guests are redirected to the login page', function () {
        $this->get('/dashboard')->assertRedirect('/login');
    });

    it('allows authenticated users to visit the dashboard', function () {
        $this->actingAs($user = User::factory()->create());

        $this->get('/dashboard')->assertOk();
    });
});
