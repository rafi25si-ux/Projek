<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Studio extends Model
{
    protected $guarded = ['id'];

    public function fotos()
    {
        return $this->hasMany(StudioFoto::class);
    }

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_studio');
    }

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class);
    }

    public function favorits()
    {
        return $this->hasMany(Favorit::class);
    }
}