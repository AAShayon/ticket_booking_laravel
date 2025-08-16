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

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function getImageAttribute($value)
    {
        if ($value) {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            return asset('storage/' . $value);
        }
        return null;
    }
}