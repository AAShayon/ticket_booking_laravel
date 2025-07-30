<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'operator_name',
        'route_details',
        'fare_details',
        'admin_commission_percentage',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
