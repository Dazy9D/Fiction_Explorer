<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    public function contents()
    {
        return $this->belongsToMany(Content::class, 'content_genre');
    }
}
