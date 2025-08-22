<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class UserController extends Controller
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
        return view('user.index', compact('contents', 'search', 'type', 'filter'));
    }


    // Show details of a single content

    public function show($id)
    {
        $content = Content::findOrFail($id);
        return view('user.show', compact('content'));
    }

}
        