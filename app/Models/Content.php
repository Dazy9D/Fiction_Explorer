<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'title',
        'description',
        'release_date',
        'type'
    ];
    protected $casts = ['release_date' => 'date'];
}
