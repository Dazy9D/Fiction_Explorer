<!DOCTYPE html>
<html>

<head>
    <title>{{ $content->title }} | Fiction Explorer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 15px;
            color: #333;
            background: #f9f9f9;
        }

        h1 {
            margin-top: 30px;
        }

        a {
            color: #1a73e8;
            text-decoration: none;
        }

        .poster-img {
            max-width: 300px;
            display: block;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        button {
            display: inline-block;
            padding: 4px 12px;
            margin-right: 10px;
            background-color: #1a73e8;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .watchlist-btn {
            background-color: #388e3c;
        }

        .watchlist-btn.remove {
            background-color: #d32f2f;
        }

        .watchlist-btn:hover {
            opacity: 0.9;
        }

        .watchlist-link {
            margin-bottom: 15px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <h1>{{ $content->title }}</h1>

    {{-- Display poster if available --}}
    @if ($content->poster)
        <img src="{{ asset('storage/' . $content->poster) }}" alt="{{ $content->title }} Poster" class="poster-img">
    @else
        <p><em>No poster available.</em></p>
    @endif

    <p><strong>Type:</strong> {{ ucfirst($content->type) }}</p>
    <p><strong>Release Date:</strong> {{ $content->release_date->format('d M, Y') }}</p>
    <strong>Genres:</strong>
    @foreach ($content->genres as $genre)
        {{ $genre->name }}@if (!$loop->last)
            ,
        @endif
    @endforeach

    <p><strong>Rating:</strong> {{ $content->rating ?? 'N/A' }}</p>
    @auth
        @php
            $inWatchlist = auth()->user()->watchlist->contains($content->id);
        @endphp

        @if (!$inWatchlist)
            <form action="{{ route('watchlist.add', $content->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="watchlist-btn">Add to Watchlist</button>
            </form>
        @else
            <form action="{{ route('watchlist.remove', $content->id) }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="watchlist-btn remove">Remove from Watchlist</button>
            </form>
        @endif
    @endauth

    @if ($content->description)
        <p>{{ $content->description }}</p>
    @else
        <p>No description available.</p>
    @endif

    <a href="{{ route('user.index') }}" style="display: inline-block; margin-top: 20px;">&larr; Back to List</a>
</body>

</html>
