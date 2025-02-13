<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\CreateEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Resources\Event\EventResource;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admins']);
    }

    public function index(): AnonymousResourceCollection
    {
        $events = Event::orderBy('start_date', 'desc')
            ->dynamicPaginate();

        return EventResource::collection($events);
    }

    public function store(CreateEventRequest $request): JsonResponse
    {
        $event = Event::create($request->validated());

        return $this->responseCreated(null, new EventResource($event));
    }

    public function show(Event $event): JsonResponse
    {
        return $this->responseSuccess(
            null,
            new EventResource($event)
        );
    }

    public function update(UpdateEventRequest $request, Event $event): JsonResponse
    {
        $event->update($request->validated());

        return $this->responseSuccess(null, new EventResource($event));
    }

    public function destroy(Event $event): JsonResponse
    {
        //for chnages in future, you can add you conditions here before the delete operation.
        // e.g. check if the event status is 'published' you can't delete it
        $event->delete();

        return $this->responseDeleted();
    }
}
