<!DOCTYPE html>
<html>

<head>
    <title>My Watched Movies and Series</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 30px auto;
            padding: 0 15px;
            background-color: #f9f9f9;
            color: #333;
        }

        .poster-img {
            max-width: 150px;
            display: block;
            margin-bottom: 20px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <h1>My Watched Movies and Series</h1>

    @if ($contents->count() > 0)
        @foreach ($contents as $content)
            <div class="content-item">
                <h2>{{ $content->title }}</h2>
                @if ($content->poster)
                    <img src="{{ asset('storage/' . $content->poster) }}" alt="{{ $content->title }} Poster"
                        class="poster-img">
                @else
                    <p><em>No poster available.</em></p>
                @endif
                <p><strong>Type:</strong> {{ ucfirst($content->type) }}</p>
                <p><strong>Release Date:</strong> {{ \Carbon\Carbon::parse($content->release_date)->format('d M, Y') }}
                </p>
                <p><strong>Rating:</strong> {{ $content->rating ?? 'N/A' }}</p>
                <p><strong>Genres:</strong>
                    @foreach ($content->genres as $genre)
                        {{ $genre->name }}@if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                </p>
                <form method="POST" action="{{ route('content.rate', $content->id) }}">
                    @csrf
                    <label>Rate this content:</label>
                    <select name="rating">
                        @for ($i = 1.0; $i <= 10; $i+=0.5)
                            <option value="{{ $i }}"
                                {{ optional(auth()->user()->watchedContents()->where('content_id', $content->id)->first())->pivot->rating == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                    <button type="submit">Submit Rating</button>
                </form>
            </div>
        @endforeach
    @else
        <p>You have not marked any movies or series as watched.</p>
    @endif

    <a href="{{ route('user.index') }}">Back to All Contents</a>
</body>

</html>
