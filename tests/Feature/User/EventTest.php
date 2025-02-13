<?php

namespace Tests\Feature\User;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\BaseTestCase;

class EventTest extends BaseTestCase
{
    use RefreshDatabase;

    protected string $endpoint = '/api/events';

    protected string $table_name = 'events';

    public function testAdminCanViewAllEvents(): void
    {
        $this->loginAsUser();

        $events = Event::factory(random_int(2, 10))->create();

        $this->json('GET', $this->endpoint)
            ->assertStatus(200)
            ->assertSee($events->random()->name);
    }
}
