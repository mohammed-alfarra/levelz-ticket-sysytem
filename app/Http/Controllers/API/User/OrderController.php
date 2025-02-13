<?php

namespace App\Http\Controllers\API\User;

use App\Actions\Ticket\BuyTicket;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ticket\BuyTicketRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(private BuyTicket $buyTicket)
    {
        $this->middleware(['auth:api']);
    }

    public function purchase(BuyTicketRequest $request, Event $event): JsonResponse
    {
        $order = $this->buyTicket->execute($request, $event);

        return $this->responseCreated(null, new OrderResource($order->load('tickets')));
    }
}
