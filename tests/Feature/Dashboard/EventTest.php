<?php

namespace Tests\Feature\Dashboard;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\BaseTestCase;

class EventTest extends BaseTestCase
{
    use RefreshDatabase;

    protected string $endpoint = '/api/dashboard/events';

    protected string $table_name = 'events';

    public function testAdminCanViewAllEvents(): void
    {
        $this->loginAsAdmin();

        $events = Event::factory(random_int(2, 10))->create();

        $this->json('GET', $this->endpoint)
            ->assertStatus(200)
            ->assertSee($events->random()->name);
    }

    public function testAdminCanCreateEvent(): void
    {
        $this->loginAsAdmin();

        $payload = Event::factory()->make()->toArray();

        $this->json('POST', $this->endpoint, $payload)
            ->assertStatus(201)
            ->assertSee($payload['name'])
            ->assertSee($payload['description']);

        $this->assertDatabaseCount('events', 1);
    }

    public function testAdminCanShowEvent(): void
    {
        $this->loginAsAdmin();

        $event = Event::factory()->create();

        $this->json('GET', "{$this->endpoint}/{$event->id}")
            ->assertStatus(200)
            ->assertSee($event->name);
    }

    public function testAdminCanUpdateEvent(): void
    {
        $this->loginAsAdmin();

        $event = Event::factory()->create();

        $payload = Event::factory()->make()->toArray();

        $this->json('PUT', "{$this->endpoint}/{$event->id}", $payload)
            ->assertStatus(200)
            ->assertSee($payload['name']);

        $this->assertDatabaseMissing('events', ['name' => $event->name]);
    }

    public function testAdminCanDeleteEvent(): void
    {
        $this->loginAsAdmin();

        $event = Event::factory()->create();

        $this->json('DELETE', "{$this->endpoint}/{$event->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
        $this->assertDatabaseCount('events', 0);
    }
}
