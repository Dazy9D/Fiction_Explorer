<!DOCTYPE html>
<html>
<head>
    <title>My Watched Movies and Series</title>
</head>
<body>
    <h1>My Watched Movies and Series</h1>

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
            </div>
        @endforeach
    @else
        <p>You have not marked any movies or series as watched.</p>
    @endif

    <a href="{{ route('user.index') }}">Back to All Contents</a>
</body>
</html>
