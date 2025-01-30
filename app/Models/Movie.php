<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = ['title', 'show_date', 'show_time'];

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function seats() {
        return $this->hasManyThrough(Seat::class, Booking::class, 'movie_id', 'id', 'id', 'seat_id');
    }
}
