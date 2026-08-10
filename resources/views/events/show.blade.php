<h1>{{ $event->title }}</h1>

<p>
    <strong>Venue:</strong>
    {{ $event->venue->name }}
</p>

<p>
    <strong>Description:</strong>
    {{ $event->description ?? 'No description' }}
</p>

<p>
    <strong>Starts on:</strong>
    {{ $event->starts_on }}
</p>

<p>
    <strong>Capacity:</strong>
    {{ $event->capacity ?? 'Not specified' }}
</p>

<a href="{{ route('events.edit', $event) }}">Edit</a>

<a href="{{ route('events.index') }}">Back to events</a>