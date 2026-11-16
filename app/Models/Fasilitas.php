<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $table = 'fasilitas';
    protected $guarded = ['id'];

    public function studios()
    {
        return $this->belongsToMany(Studio::class, 'fasilitas_studio');
    }
}