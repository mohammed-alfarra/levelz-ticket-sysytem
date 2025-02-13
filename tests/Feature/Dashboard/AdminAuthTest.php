<?php

namespace Tests\Feature\Dashboard;

use App\Enums\Guards;
use App\Models\Admin;
use Tests\Feature\BaseTestCase;

class AdminAuthTest extends BaseTestCase
{
    protected string $endpoint = '/api/dashboard/';

    public function testSuperAdminLogin(): void
    {
        $admin = Admin::factory()->create();

        $payload = [
            'email' => $admin->email,
            'password' => 'password',
        ];

        $this->json('POST', "{$this->endpoint}login", $payload)
            ->assertSee('access_token')
            ->assertStatus(200);

        $this->assertAuthenticated(Guards::ADMIN);
    }
}
