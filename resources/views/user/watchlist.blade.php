<!DOCTYPE html>
<html>
<head>
    <title>My Watchlist</title>
    <style>
        .content-item {
            margin-bottom: 25px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
        }
        .remove-btn {
            background: #d32f2f;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 6px 12px;
            cursor: pointer;
        }
        .back-link {
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <h1>My Watchlist</h1>

    @if ($contents->count() > 0)
        @foreach ($contents as $content)
            <div class="content-item">
                <h2>{{ $content->title }}</h2>
                <p><strong>Type:</strong> {{ ucfirst($content->type) }}</p>
                <p><strong>Release Date:</strong> {{ \Carbon\Carbon::parse($content->release_date)->format('d M, Y') }}</p>
                <p><strong>Rating:</strong> {{ $content->rating ?? 'N/A' }}</p>
                <p><strong>Genres:</strong> 
                    @foreach ($content->genres as $genre)
                        {{ $genre->name }}@if (!$loop->last), @endif
                    @endforeach
                </p>
                <p>{{ \Illuminate\Support\Str::limit($content->description, 100) }}</p>
                <form action="{{ route('watchlist.remove', $content->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="remove-btn">Remove from Watchlist</button>
                </form>
            </div>
        @endforeach
    @else
        <p>Your watchlist is empty.</p>
    @endif

    <a href="{{ route('user.index') }}" class="back-link">Back to All Contents</a>
</body>
</html>
