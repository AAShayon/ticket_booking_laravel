<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_email',
        'contact_phone',
        'admin_commission_percentage',
        'user_id',
        'nid',
        'address',
        'transport_business_license',
        'logo',
    ];

    public function routes()
    {
        return $this->hasMany(Route::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function getLogoAttribute($value)
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