<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'operator_id',
        'model_number',
        'type',
        'capacity',
        'image',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
