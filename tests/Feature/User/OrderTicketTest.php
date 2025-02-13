<?php

namespace Tests\Feature\User;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Feature\BaseTestCase;

class OrderTicketTest extends BaseTestCase
{
    use RefreshDatabase;

    protected string $endpoint = '/api/events';

    protected string $table_name = 'events';

    use RefreshDatabase;

    public function testUserCanSuccessfullyPurchaseTickets(): void
    {
        $event = Event::factory()->create();

        $initQuota = $event->quota;

        $this->loginAsUser();

        $payload = [
            'quantity' => 3
        ];

        $this->json('POST', "{$this->endpoint}/{$event->id}/purchase", $payload)
            ->assertStatus(201);

        $this->assertCount($payload['quantity'], Order::first()->tickets);
        $event->refresh();
        $this->assertEquals(($initQuota - $payload['quantity']), $event->quota);
    }

    public function testUserCannotPurchaseMoreThanFiveTicketsAtOnce(): void
    {
        $event = Event::factory()->create();

        $this->loginAsUser();

        $payload = [
            'quantity' => 6
        ];

        $this->json('POST', "{$this->endpoint}/{$event->id}/purchase", $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The quantity must not be greater than 5.',
            ]);
    }

    public function testUserCannotPurchaseTicketsIfNoEnoughTicketsAvailable(): void
    {
        $event = Event::factory()->create(['quota' => 1]);

        Ticket::factory()->count(9)->create(['event_id' => $event->id]);

        $this->loginAsUser();

        $payload = [
            'quantity' => 3
        ];

        $event->refresh();

        $this->json('POST', "{$this->endpoint}/{$event->id}/purchase", $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Only 1 tickets are available.',
            ]);
    }

    public function testUserCannotPurchaseTicketsIfEventIsSoldOut(): void
    {
        $event = Event::factory()->create(['quota' => 0]);

        $this->loginAsUser();

        $payload = [
            'quantity' => 1
        ];

        $this->json('POST', "{$this->endpoint}/{$event->id}/purchase", $payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'This event is sold out.',
            ]);
    }

    public function testQuotaShouldNotBeDecreasedIfTicketCreationFails(): void
    {
        $event = Event::factory()->create(['quota' => 100]);

        DB::shouldReceive('transaction')
            ->once()
            ->andThrow(new \Exception('Failed to create tickets.'));

        $this->loginAsUser();

        $payload = [
            'quantity' => 3
        ];

        $this->json('POST', "{$this->endpoint}/{$event->id}/purchase", $payload)
            ->assertStatus(500)
            ->assertJson([
                'message' => 'Failed to create tickets.',
            ]);

        $event->refresh();
        $this->assertEquals(100, $event->quota);
    }
}
