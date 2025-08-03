<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class ContentController extends Controller
{
    /**
     * Show the list of contents with filters for type and release status.
     */
    public function index(Request $request)
    {
        // Fetch query parameters or set defaults
        $search = $request->input('q', '');
        $type = $request->input('type', 'all');       // movie, series, all
        $filter = $request->input('filter', 'all');   // all, released, upcoming

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
        
        // Sort latest release date descending
        $contents = $query->orderBy('release_date', 'desc')->paginate(10);

        // Pass filters & results to the view
        return view('contents.index', compact('contents', 'search', 'type', 'filter'));
    }

    /**
     * Show details of a single content.
     */
    public function show($id)
    {
        $content = Content::findOrFail($id);
        return view('contents.show', compact('content'));
    }
}
