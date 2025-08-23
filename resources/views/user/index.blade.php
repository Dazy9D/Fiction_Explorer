<!DOCTYPE html>
<html>

<head>
    <title>Fiction Explorer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 15px;
            background-color: #f9f9f9;
            color: #333;
        }

        h1 {
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .buttons {
            display: inline;
            gap: 10px;
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

        .action-buttons {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .action-buttons button {
            padding: 6px 18px;
            border: none;
            outline: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: filter 0.2s;
        }

        .action-buttons .watchlist-btn {
            background-color: #1a73e8;
            color: white;
        }

        .action-buttons .logout-btn {
            background-color: #d32f2f;
            color: white;
        }

        .action-buttons button:hover {
            filter: brightness(0.92);
        }


        .btn-add,
        .btn-logout {
            padding: 8px 18px;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-add {
            background-color: #1a73e8;
        }

        .btn-add:hover {
            background-color: #155ab6;
        }

        .btn-logout {
            background-color: #e53e3e;
        }

        .btn-logout:hover {
            background-color: #9b2c2c;
        }

        .btn-edit {
            background-color: #fbbf24;
            color: #1a202c;
            padding: 4px 8px;
            font-size: 0.9rem;
            border-radius: 4px;
            font-weight: bold;
            text-decoration: none;
            margin-left: 10px;
        }

        .btn-edit:hover {
            background-color: #d97706;
            color: white;
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

    <div class="header">
        <h1>Fiction Explorer</h1>

        @auth
            <div class="action-buttons">
                <a href="{{ route('watchlist.show') }}">
                    <button class="watchlist-btn">View My Watchlist</button>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        @endauth
    </div>


    <!-- Search & Filters Form -->
    <form method="GET" action="{{ route('user.index') }}">
        <input type="text" name="q" value="{{ old('q', $search) }}" placeholder="Search by title..."
            style="width: 250px;">

        <select name="type">
            <option value="all" {{ $type === 'all' ? 'selected' : '' }}>All Types</option>
            <option value="movie" {{ $type === 'movie' ? 'selected' : '' }}>Movies</option>
            <option value="series" {{ $type === 'series' ? 'selected' : '' }}>Series</option>
        </select>

        <select name="filter">
            <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All</option>
            <option value="released" {{ $filter === 'released' ? 'selected' : '' }}>Released</option>
            <option value="upcoming" {{ $filter === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
        </select>

        <br>
        <label for="genre">Genre:</label>
        <select name="genre" id="genre">
            <option value="">All Genres</option>
            @foreach ($genres as $genre)
                <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                    {{ $genre->name }}
                </option>
            @endforeach
        </select>

        <label for="rating">Minimum Rating:</label>
        <select name="rating" id="rating">
            <option value="">Any Rating</option>
            @for ($r = 1; $r <= 10; $r += 0.5)
                <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>
                    {{ number_format($r, 1) }}+
                </option>
            @endfor
        </select>

        <button type="submit">Filter</button>
        <a href="{{ route('user.index') }}" style="margin-left: 10px;">Reset</a>
    </form>

    @if ($contents->count())
        <ul>
            @foreach ($contents as $content)
                <li>
                    <a href="{{ route('user.show', $content->id) }}" style="font-size: 18px; font-weight: bold;">
                        {{ $content->title }}
                    </a>
                    <div>
                        <small>
                            Type: <strong>{{ ucfirst($content->type) }}</strong> |
                            Release Date: <strong>{{ $content->release_date->format('d M, Y') }}</strong>
                            Genres:

                            <strong>
                                @foreach ($content->genres as $genre)
                                    {{ $genre->name }}@if (!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            </strong>

                            <p><strong>Rating:</strong> {{ $content->rating ?? 'N/A' }}</p>

                        </small>
                    </div>
                    @if ($content->description)
                        <p>{{ \Illuminate\Support\Str::limit($content->description, 100) }}</p>
                    @endif
                </li>
            @endforeach
        </ul>

        <div class="pagination">
            {{ $contents->appends(request()->input())->links() }}
        </div>
    @else
        <p>No results found.</p>
    @endif
</body>

</html>
