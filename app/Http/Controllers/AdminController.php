<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        // Fetch query parameters or set defaults
        $search = $request->input('q', '');
        $type = $request->input('type', 'all');
        $filter = $request->input('filter', 'all');

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

        // Sort Alphabetically and loads only 10 per page
        $contents = $query->orderBy('title', 'asc')->paginate(10);

        // Pass filters & results to the view
        return view('admin.index', compact('contents', 'search', 'type', 'filter'));
    }


    // Show details of a single content

    public function show($id)
    {
        $content = Content::findOrFail($id);
        return view('admin.show', compact('content'));
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
            'release_date' => 'required|date',
            'type' => 'required|in:movie,series',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
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
            'release_date' => 'required|date',
            'type' => 'required|in:movie,series',
            'genres' => 'nullable|array',
            'genres.*' => 'exists:genres,id',
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
