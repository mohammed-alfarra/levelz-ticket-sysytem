<?php

namespace App\Actions\Ticket;

use App\Http\Requests\Ticket\BuyTicketRequest;
use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class BuyTicket
{
    //Note: we can also use repositories with DI for Event, Order, and Ticket instead-of calling Eloquent

    private const MAX_TICKETS_PER_ORDER = 5;

    //This logic for the given test denomination
    //But there's should be extra fields in Event, Ticket, and Order
    //Aslo there's should be extar tables e.g. Payment(Transaction), Attendees ...etc
    //Like we can add 'reserved' status for Ticket during purchase
    //and if payment is not confirmed for example within e.g.(10 minutes) we can auto-release them using a scheduled job
    public function execute(BuyTicketRequest $request, Event $event): Order
    {
        $UserID = auth()->id();
        $quantity = $request->get('quantity');

        //using try-catch here is unnecessary because of database transaction BUT I add it for throwing custom errors if needed
        try {
            return DB::transaction(function () use ($UserID, $event, $quantity) {
                //Fetch and lock to prevent purchase tickets concurrently
                //Note:We can aslo use Redis as lock manager here
                //or atomic SQL update or versioning
                $event = Event::where('id', $event->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $this->validatePurchase($event, $quantity);

                $order = Order::create([
                    'user_id' => $UserID,
                    'event_id' => $event->id,
                ]);

                $tickets = $this->reserveTickets($order, $event, $quantity);

                if (!$tickets) {
                    throw new Exception("Failed to create tickets.");
                }

                $event->update(['quota' => DB::raw("quota - {$quantity}")]);

                return $order;
            });
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function validatePurchase(Event $event, int $quantity): void
    {
        if ($quantity > self::MAX_TICKETS_PER_ORDER) {
            throw new UnprocessableEntityHttpException('You cannot purchase more than ' . self::MAX_TICKETS_PER_ORDER . ' tickets per order.');
        }

        if ($event->quota <= 0) {
            throw new UnprocessableEntityHttpException('This event is sold out.');
        }

        if ($quantity > $event->quota) {
            throw new UnprocessableEntityHttpException("Only {$event->quota} tickets are available.");
        }
    }

    private function reserveTickets(Order $order, Event $event, int $quantity): bool
    {
        $tickets = array_map(fn() => [
            'event_id'    => $event->id,
            'order_id'    => $order->id,
            'created_at'  => now(),
            'updated_at'  => now(),
        ], range(1, $quantity));

        return Ticket::insert($tickets);
    }
}
