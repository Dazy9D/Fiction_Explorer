<!DOCTYPE html>
<html>

<head>
    <title>Fiction Explorer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 700px;
            margin: 30px auto;
            padding: 0 15px;
            color: #333;
            background: #f9f9f9;
        }

        h1 {
            margin-top: 30px;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        li {
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        a {
            color: #1a73e8;
            text-decoration: none;
        }

        a.button {
            padding: 5px 10px;
            color: #fff;
            background: #1a73e8;
            border: none;
            border-radius: 3px;
            text-decoration: none;
        }

        form {
            margin-bottom: 20px;
        }

        select,
        input[type="text"] {
            padding: 5px;
        }

        .pagination {
            margin: 20px 0;
        }

        .pagination ul {
            display: flex;
            gap: 5px;
        }

        .pagination li {
            list-style: none;
        }

        .btn-add {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 16px;
            background: #1a73e8;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-add:hover {
            background: #155ab6;
        }
    </style>
</head>

<body>
    <h1>Fiction Explorer</h1>

    <a href="{{ route('contents.create') }}" class="btn-add">
        + Add New
    </a>


    <!-- Search & Filters Form -->
    <form method="GET" action="{{ route('contents.index') }}">
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

        <button type="submit">Filter</button>
        <a href="{{ route('contents.index') }}" style="margin-left: 10px;">Reset</a>
    </form>

    @if ($contents->count())
        <ul>
            @foreach ($contents as $content)
                <li>
                    <a href="{{ route('contents.show', $content->id) }}" style="font-size: 18px; font-weight: bold;">
                        {{ $content->title }}
                    </a>
                    <div>
                        <small>
                            Type: <strong>{{ ucfirst($content->type) }}</strong> |
                            Release Date: <strong>{{ $content->release_date->format('d M, Y') }}</strong>
                        </small>
                    </div>
                    @if ($content->description)
                        <p>{{ \Illuminate\Support\Str::limit($content->description, 100) }}</p>
                    @endif
                </li>
            @endforeach
        </ul>

        <!-- Pagination -->
        <div class="pagination">
            {{ $contents->appends(request()->input())->links() }}
        </div>
    @else
        <p>No results found.</p>
    @endif
</body>

</html>
