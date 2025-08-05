<!DOCTYPE html>
<html>
<head>
    <title>{{ $content->title }} | Fiction Explorer</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 700px; 
            margin: 30px auto; 
            padding: 0 15px; 
            color: #333; 
            background: #f9f9f9;
        }
        h1 { margin-top: 30px; }
        a { color: #1a73e8; text-decoration: none; }
        .poster-img { 
            max-width: 300px; 
            display: block; 
            margin-bottom: 20px; 
            border-radius: 8px; 
        }
    </style>
</head>
<body>
    <h1>{{ $content->title }}</h1>

    {{-- Display poster if available --}}
    @if ($content->poster)
        <img 
            src="{{ asset('storage/' . $content->poster) }}" 
            alt="{{ $content->title }} Poster" 
            class="poster-img"
        >
    @else
        <p><em>No poster available.</em></p>
    @endif

    <p><strong>Type:</strong> {{ ucfirst($content->type) }}</p>
    <p><strong>Release Date:</strong> {{ $content->release_date->format('d M, Y') }}</p>

    @if ($content->description)
        <p>{{ $content->description }}</p>
    @else
        <p>No description available.</p>
    @endif

    <a href="{{ route('contents.index') }}" style="display: inline-block; margin-top: 20px;">&larr; Back to List</a>
</body>
</html>
