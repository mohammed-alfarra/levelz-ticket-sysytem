<?php

namespace Tests\Feature;

use App\Enums\Guards;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BaseTestCase extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function loginAsAdmin(): Admin
    {
        $admin = Admin::factory()->create();

        $this->actingAs($admin, Guards::ADMIN);

        return $admin;
    }

    public function loginAsUser(): User
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    public function markTestSkippedSQlLite(string $reason): void
    {
        $this->markTestSkipped($reason);
    }
}
