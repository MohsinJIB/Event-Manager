<!DOCTYPE html>
<html>
<head>
    <title>Events</title>
</head>
<body>
<h1>Events</h1>

@if (session('ok'))
    <p>{{ session('ok') }}</p>
@endif

<a href="{{ route('events.create') }}">
    Create New Event
</a>
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Venue</th>
            <th>Starts on</th>
            <th>Capacity</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($events as $event)
            <tr>
                <td>
                    <a href="{{ route('events.show', $event) }}">
                        {{ $event->title }}
                    </a>
                </td>

                <td>{{ $event->venue->name }}</td>
                <td>{{ $event->starts_on }}</td>
                <td>{{ $event->capacity }}</td>

                <td>
                    <a href="{{ route('events.edit', $event) }}">
                        Edit
                    </a>

                    <form
                        method="POST"
                        action="{{ route('events.destroy', $event) }}"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $events->links() }}   {{-- pagination links --}}

</body>
</html>