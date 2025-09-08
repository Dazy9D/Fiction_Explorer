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

        $recommendation = $request->input('recommendation', false);
        if ($recommendation) {
            $user = auth()->user();
            $watchedGenreIds = $user->watchedContents()->with('genres')->get()->pluck('genres.*.id')->flatten()->unique();
            $watchlistGenreIds = $user->watchlist()->with('genres')->get()->pluck('genres.*.id')->flatten()->unique();
            $genreIds = $watchedGenreIds->merge($watchlistGenreIds)->unique();

            if ($genreIds->count() > 0) {
                $alreadySeenIds = $user->watchedContents->pluck('id')->merge($user->watchlist->pluck('id'))->unique();
                $query = Content::whereHas('genres', function ($q) use ($genreIds) {
                    $q->whereIn('genres.id', $genreIds);
                })
                    ->whereNotIn('id', $alreadySeenIds);
            } else {
                $query = Content::orderBy('rating', 'desc')->take(10);
            }
            $contents = $query->orderBy('title', 'asc')->paginate(10);
        } else {
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
        }

        // Sort alphabetically and paginate
        $contents = $query->orderBy('title', 'asc')->paginate(10);

        // Get genres for filter dropdown
        $genres = \App\Models\Genre::orderBy('name')->get();

        return view('user.index', compact('contents', 'search', 'type', 'filter', 'genres', 'genre', 'rating'));
    }

    public function show($id)
    {
        $content = Content::findOrFail($id);
        $content->trailer_embed_url = $this->youtubeEmbedUrl($content->trailer_url);

        return view('user.show', compact('content'));
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

    public function markAsWatched($id)
    {
        $content = Content::findOrFail($id);
        $user = auth()->user();

        $user->watchedContents()->syncWithoutDetaching([$content->id]);
        $user->watchlist()->detach($content->id);

        return back()->with('success', 'Marked as watched and removed from your watchlist.');
    }


    public function unmarkAsWatched($id)
    {
        $content = Content::findOrFail($id);
        auth()->user()->watchedContents()->detach($content->id);
        return back()->with('success', 'Unmarked as watched!');
    }

    public function showWatched()
    {
        $contents = auth()->user()->watchedContents()->with('genres')->get();
        return view('user.watched', compact('contents'));
    }

    public function downloadWatchlistPdf()
    {
        $contents = auth()->user()->watchlist()->with('genres')->get();
        $pdf = \PDF::loadView('user.watchlist_pdf', compact('contents'));
        return $pdf->download('watchlist.pdf');
    }

    public function rateWatchedContent(Request $request, $contentId)
    {
        $request->validate([
            'rating' => 'required|numeric|min:0|max:10',
        ]);

        $user = auth()->user();
        $content = Content::findOrFail($contentId);

        $user->watchedContents()->updateExistingPivot($content->id, ['rating' => $request->rating]);

        $averageRating = $content->watchedBy()->wherePivotNotNull('rating')->avg('rating');

        $content->rating = $averageRating !== null ? round($averageRating, 1) : 0;
        $content->save();

        return back()->with('success', 'Your rating has been saved.');
    }
}
