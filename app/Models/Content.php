<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'title',
        'description',
        'release_date',
        'type',
        'rating',
        'poster'
    ];
    protected $casts = ['release_date' => 'date'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'content_genre')->orderBy('name', 'asc');
    }

    public function watchlistedBy()
    {
        return $this->belongsToMany(User::class, 'watchlist')->withTimestamps();
    }

    public function watchedBy()
    {
        return $this->belongsToMany(User::class, 'watched')->withTimestamps();
    }
}
