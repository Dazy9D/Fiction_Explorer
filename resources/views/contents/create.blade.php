<!DOCTYPE html>
<html>
<head>
    <title>Add New Content | Fiction Explorer</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 30px auto; color: #333; background: #fafafa;}
        label { display: block; margin-top: 15px; }
        input, textarea, select { width: 100%; padding: 8px; margin-top: 5px;}
        button { margin-top: 20px; padding: 10px 20px; border: none; background: #1a73e8; color: #fff; border-radius: 4px;}
        .error { color: red; margin-bottom: 10px; }
        .back-link { margin-top: 15px; display: block;}
    </style>
</head>
<body>
    <h1>Add New Movie / Series</h1>

    <!-- Display errors -->
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('contents.store') }}" enctype="multipart/form-data">
        @csrf

        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="{{ old('title') }}" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description">{{ old('description') }}</textarea>

        <label for="release_date">Release Date:</label>
        <input type="date" name="release_date" id="release_date" value="{{ old('release_date') }}" required>

        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="">-- Select --</option>
            <option value="movie" {{ old('type') === 'movie' ? 'selected' : '' }}>Movie</option>
            <option value="series" {{ old('type') === 'series' ? 'selected' : '' }}>Series</option>
        </select>

        <label for="poster">Poster Image:</label>
        <input type="file" name="poster" id="poster" accept="image/*">

        <button type="submit">Add Content</button>
    </form>

    <a class="back-link" href="{{ route('contents.index') }}">&larr; Back to List</a>
</body>
</html>
