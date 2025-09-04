<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'origin',
        'destination',
        'estimated_travel_time',
        'vehicle_number',
        'time_of_day',
        'vehicle_id',
        'fare_per_seat',
        'departure_time',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
