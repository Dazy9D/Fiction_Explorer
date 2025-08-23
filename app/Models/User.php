<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function watchlist()
    {
        return $this->belongsToMany(Content::class, 'watchlist')->withTimestamps();
    }

    public function watchedContents()
    {
        return $this->belongsToMany(Content::class, 'watched')->withTimestamps();
    }
}
