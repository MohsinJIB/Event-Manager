<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
class EventController extends Controller
{
    private const EVENTS_INDEX_VERSION_KEY = 'events.index.version';
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $events = Event::with('venue')
            ->latest()
            ->paginate(10);

        return response()->json($events);
    }

    private function invalidateWebIndexCache(): void
    {
        $version = Cache::get(
            self::EVENTS_INDEX_VERSION_KEY,
            1
        );

        Cache::forever(
            self::EVENTS_INDEX_VERSION_KEY,
            $version + 1
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = Event::create($request->validated());

        $event->load('venue');

        $this->invalidateWebIndexCache();

        return response()->json([
            'message' => 'Event created successfully.',
            'data' => $event,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): JsonResponse
    {
        $event->load('venue');

        return response()->json([
            'data' => $event,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreEventRequest $request, Event $event): JsonResponse
    {
        $event->update($request->validated());

        $event->load('venue');

        $this->invalidateWebIndexCache();

        return response()->json([
            'message' => 'Event updated successfully.',
            'data' => $event,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): JsonResponse
    {
        $event->delete();

        $this->invalidateWebIndexCache();

        return response()->json([
            'message' => 'Event deleted successfully.',
        ]);
    }
}