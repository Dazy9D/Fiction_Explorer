<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Content;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('q', '');
        $type = $request->input('type', 'all');
        $filter = $request->input('filter', 'all');
        $genre = $request->input('genre', 'all');
        $rating = $request->input('rating', '');

        // Start query builder
        $query = Content::with(['genres' => function ($q) {
            $q->orderBy('name');
        }]);

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

        // Sort alphabetically and paginate
        $contents = $query->orderBy('title', 'asc')->paginate(10);

        // Get genres for filter dropdown
        $genres = \App\Models\Genre::orderBy('name')->get();

        return view('user.index', compact('contents', 'search', 'type', 'filter', 'genres', 'genre', 'rating'));
    }



    // Show details of a single content

    public function show($id)
    {
        $content = Content::findOrFail($id);
        return view('user.show', compact('content'));
    }

    public function addToWatchlist($id)
    {
        $content = Content::findOrFail($id);
        auth()->user()->watchlist()->syncWithoutDetaching([$content->id]);
        return back()->with('success', 'Added to watchlist!');
    }

    public function removeFromWatchlist($id)
    {
        $content = Content::findOrFail($id);
        auth()->user()->watchlist()->detach($content->id);
        return back()->with('success', 'Removed from watchlist!');
    }


    public function showWatchlist()
    {
        $contents = auth()->user()->watchlist()->with('genres')->get();
        return view('user.watchlist', compact('contents'));
    }
}
