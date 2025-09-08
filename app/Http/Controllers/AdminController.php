<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('q', '');
        $type = $request->input('type', 'all');
        $filter = $request->input('filter', 'all');
        $genre = $request->input('genre', 'all');
        $rating = $request->input('rating', '');

        // Start query builder
        $query = Content::query();

        if (!empty($search)) {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        if ($filter === 'released') {
            $query->whereDate('release_date', '<=', now());
        } elseif ($filter === 'upcoming') {
            $query->whereDate('release_date', '>', now());
        }

        if ($genre !== 'all' && !empty($genre)) {
            $query->whereHas('genres', function ($q) use ($genre) {
                $q->where('genres.id', $genre);
            });
        }

        if (is_numeric($rating)) {
            $query->where('rating', '>=', floatval($rating));
        }

        $contents = $query->orderBy('title', 'asc')->paginate(10);

        $genres = \App\Models\Genre::orderBy('name')->get();

        return view('admin.index', compact('contents', 'search', 'type', 'filter', 'genres', 'genre', 'rating'));
    }


    public function show($id)
    {
        $content = Content::findOrFail($id);
        $content->trailer_embed_url = $this->youtubeEmbedUrl($content->trailer_url);

        return view('admin.show', compact('content'));
    }

    private function youtubeEmbedUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        if (preg_match('/youtu\.be\/([^\?\/]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        if (preg_match('/v=([^&]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        return $url;
    }


    // Show the form to create a new movie or series
    public function create()
    {
        $genres = \App\Models\Genre::all();
        return view('admin.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trailer_url' => 'nullable|string|max:255',
            'release_date' => 'required|date',
            'type' => 'required|in:movie,series',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'rating' => 'nullable|numeric|min:0|max:10',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        // Create the content record
        $content = \App\Models\Content::create($validated);

        // Attach genres (if any)
        if ($request->filled('genres')) {
            $content->genres()->sync($request->genres);
        }

        return redirect()->route('admin.index')->with('success', 'Content added successfully!');
    }

    // Update the details of the contents
    public function edit($id)
    {
        $content = \App\Models\Content::findOrFail($id);
        $genres = \App\Models\Genre::all();

        // Pass the content (with current genres) and all genres to the view
        return view('admin.edit', compact('content', 'genres'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'trailer_url' => 'nullable|string|max:255',
            'release_date' => 'required|date',
            'type' => 'required|in:movie,series',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
            'rating' => 'nullable|numeric|min:0|max:10',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $content = \App\Models\Content::findOrFail($id);

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $content->update($validated);

        // Sync genres
        if ($request->filled('genres')) {
            $content->genres()->sync($request->genres);
        } else {
            $content->genres()->sync([]); // Remove all if none selected
        }

        return redirect()->route('admin.index')->with('success', 'Content updated successfully!');
    }
}
