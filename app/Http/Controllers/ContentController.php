<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class ContentController extends Controller
{

    public function index(Request $request)
    {
        // Fetch query parameters or set defaults
        $search = $request->input('q', '');
        $type = $request->input('type', 'all');
        $filter = $request->input('filter', 'all');

        // Start query builder
        $query = Content::query();

        // If searching by name
        if (!empty($search)) {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        // Filter by type if specified
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        // Filter by release status
        if ($filter === 'released') {
            $query->whereDate('release_date', '<=', now());
        } elseif ($filter === 'upcoming') {
            $query->whereDate('release_date', '>', now());
        }

        // Sort Alphabetically
        $contents = $query->orderBy('title', 'asc')->paginate(10);

        // Pass filters & results to the view
        return view('contents.index', compact('contents', 'search', 'type', 'filter'));
    }


    // Show details of a single content

    public function show($id)
    {
        $content = Content::findOrFail($id);
        return view('contents.show', compact('content'));
    }


    // Show the form to create a new movie or series
    public function create()
    {
        return view('contents.create');
    }

    // Store the new movie or series
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_date' => 'required|date',
            'type' => 'required|in:movie,series',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('poster')) {
            $posterPath = $request->file('poster')->store('posters', 'public');
            $validated['poster'] = $posterPath;
        }

        Content::create($validated);

        return redirect()->route('contents.index')->with('success', 'Content added successfully!');
    }
}
