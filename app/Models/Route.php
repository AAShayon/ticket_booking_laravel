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
        'fare',
        'estimated_travel_time',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
