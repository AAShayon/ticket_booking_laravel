<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_station',
        'to_station',
        'journey_date',
        'seat_type',
        'number_of_seats',
        'total_fare',
        'status',
        'route_id',
        'seat_number',
        'payment_method',
        'payment_name',
        'transaction_id',
    ];

    protected $casts = [
        'seat_number' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function pnr()
    {
        return $this->hasOne(Pnr::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
