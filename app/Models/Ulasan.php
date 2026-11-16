<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }
}