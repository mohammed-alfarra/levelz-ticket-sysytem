<?php

namespace Tests\Feature\User;

use App\Models\User;
use Tests\Feature\BaseTestCase;

class UserAuthTest extends BaseTestCase
{
    protected string $endpoint = '/api/';

    protected string $table_name = 'users';

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUserLogin(): void
    {
        $user = User::factory()->create([
            'is_active' => true,
        ]);

        $body = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $this->json('POST', "{$this->endpoint}login", $body)
            // ->assertSee('token')->dd()
            ->assertStatus(200);

        $this->assertAuthenticated();
    }

    public function testUserCantLoginWhenIsNotActive(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'is_active' => false,
        ]);

        $body = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $this->json('POST', "{$this->endpoint}login", $body)
            ->assertStatus(409);
    }

    public function testUserCantLoginWhenDataIsWrong(): void
    {
        $user = User::factory()->create();

        $body = [
            'email' => $user->email,
            'password' => $this->faker->password,
        ];

        $this->json('POST', "{$this->endpoint}login", $body)
            ->assertStatus(422);
    }
}
