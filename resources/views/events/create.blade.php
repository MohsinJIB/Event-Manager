<h1>Create Event</h1>

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('events.store') }}">
    @csrf

    <div>
        <label for="venue_id">Venue</label>

        <select id="venue_id" name="venue_id">
            <option value="">Choose a venue</option>

            @foreach ($venues as $venue)
                <option value="{{ $venue->id }}"
                    @selected(old('venue_id') == $venue->id)>
                    {{ $venue->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="title">Title</label>
        <input
            id="title"
            type="text"
            name="title"
            value="{{ old('title') }}"
        >
    </div>

    <div>
        <label for="description">Description</label>
        <textarea
            id="description"
            name="description"
        >{{ old('description') }}</textarea>
    </div>

    <div>
        <label for="starts_on">Starts on</label>
        <input
            id="starts_on"
            type="date"
            name="starts_on"
            value="{{ old('starts_on') }}"
        >
    </div>

    <div>
        <label for="capacity">Capacity</label>
        <input
            id="capacity"
            type="number"
            name="capacity"
            min="0"
            value="{{ old('capacity') }}"
        >
    </div>

    <button type="submit">Create Event</button>
</form>

<a href="{{ route('events.index') }}">Back to events</a>