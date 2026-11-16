<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudioFoto extends Model
{
    protected $guarded = ['id'];

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }
}
