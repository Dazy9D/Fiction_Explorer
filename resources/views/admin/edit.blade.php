<!DOCTYPE html>
<html>

<head>
    <title>Edit Content</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 30px auto;
            color: #333;
            background: #fafafa;
        }

        label {
            display: block;
            margin-top: 15px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            background: #1a73e8;
            color: #fff;
            border-radius: 4px;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <h1>Edit Movie / Series</h1>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.update', $content->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="{{ old('title', $content->title) }}" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description">{{ old('description', $content->description) }}</textarea>

        <label for="release_date">Release Date:</label>
        <input type="date" name="release_date" id="release_date"
            value="{{ old('release_date', $content->release_date->format('Y-m-d')) }}" required>

        <label for="type">Type:</label>
        <select name="type" id="type" required>
            <option value="">-- Select --</option>
            <option value="movie" {{ old('type', $content->type) == 'movie' ? 'selected' : '' }}>Movie</option>
            <option value="series" {{ old('type', $content->type) == 'series' ? 'selected' : '' }}>Series</option>
        </select>

        <label for="genres">Genres:</label>
        <select name="genres[]" id="genres" multiple>
            @foreach ($genres as $genre)
                <option value="{{ $genre->id }}"
                    {{ collect(old('genres', $content->genres->pluck('id')))->contains($genre->id) ? 'selected' : '' }}>
                    {{ $genre->name }}
                </option>
            @endforeach
        </select>
        <small>Hold Ctrl to select multiple genres</small>

        <label for="rating">Rating:</label>
        <input type="number" name="rating" id="rating" value="{{ old('rating') }}" min="0" max="10"
            step="0.1" placeholder="e.g., 8.0">

        <label for="poster">Poster Image (choose to replace):</label>
        <input type="file" name="poster" id="poster" accept="image/*">

        @if ($content->poster)
            <div>
                <img src="{{ asset('storage/' . $content->poster) }}" alt="Current Poster"
                    style="max-width:120px; margin:10px 0;">
            </div>
        @endif

        <button type="submit">Update Content</button>
    </form>
    <br>
    <a class="back-link" href="{{ route('admin.index') }}">&larr; Back to List</a>
</body>

</html>
