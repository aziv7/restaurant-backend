<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryDistance extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'distance',
        'price',
    ];

    public function restaurant_info()
    {
        return $this->belongsToOneOrZero(RestaurantInfo::class);
    }
}
