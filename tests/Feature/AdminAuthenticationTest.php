<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    public function test_guest_admin_dashboard_redirects_to_admin_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect(route('admin.login'));
    }
}
