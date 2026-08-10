<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEventRequest;
use App\Models\Venue;
use Illuminate\Support\Facades\Cache; // Import the Cache facade

class EventController extends Controller
{
    private const EVENTS_INDEX_VERSION_KEY = 'events.index.version'; // constant for the cache version key
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // (N+1: 1 + 10 queries)
        $events = Event::latest()->paginate(10);

        // eager-load the venue to avoid N+1 (see the performance section)
        $events = Event::with('venue')->latest()->paginate(10);
      
        // caching the events index page
        $page = max(1, request()->integer('page', 1)); //Determine the requested page
        $version = Cache::rememberForever( //changes only when your invalidation method updates it
            self::EVENTS_INDEX_VERSION_KEY,
            fn () => 1
        ); //Retrieve the current cache version

        $events = Cache::remember(
            "events.index.v{$version}.page.{$page}", //Generate the cache key
            now()->addMinutes(10), //Set the cache expiration time
            fn () => Event::with('venue') ->latest() ->paginate(10)
        ); //Retrieve or create the page cache

        return view('events.index', compact('events'));
    }

    //changes the version number
    private function invalidateEventsIndexCache(): void 
    {
    $version = Cache::get( //Retrieve the current version
        self::EVENTS_INDEX_VERSION_KEY,
        1 //Default to 1 if not set
    );

    Cache::forever( //Update the version number in the cache
        self::EVENTS_INDEX_VERSION_KEY,
        $version + 1
    );
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $venues = Venue::orderBy('name')->get();

        return view('events.create', compact('venues'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        // validation already passed inside the Form Request
        Event::create($request->validated());
        $this->invalidateEventsIndexCache();
        return redirect()->route('events.index')->with('ok', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load('venue');

        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $venues = Venue::orderBy('name')->get();
        return view('events.edit', compact('event', 'venues'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreEventRequest $request, Event $event)
    {
        $event->update($request->validated());
        $this->invalidateEventsIndexCache();
        return redirect()->route('events.index')->with('ok', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        $this->invalidateEventsIndexCache();
        return redirect()->route('events.index')->with('ok', 'Event deleted successfully!');
    }
}
