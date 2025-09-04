<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pnr extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'pnr_number',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
