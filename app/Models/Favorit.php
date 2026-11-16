<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorit extends Model
{
    protected $guarded = ['id'];

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }
}