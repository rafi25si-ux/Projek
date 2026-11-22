<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    protected $guarded = ['id'];

    public static function batalkanKedaluwarsa(): void
    {
        self::where('status', 'Menunggu pembayaran')
            ->where('created_at', '<=', now()->subMinutes(5))
            ->update(['status' => 'Dibatalkan']);
    }

    public function getBatasBayarAttribute()
    {
        return $this->created_at->copy()->addMinutes(5);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class)->latestOfMany();
    }

    public function ulasan()
    {
        return $this->hasOne(Ulasan::class);
    }
}