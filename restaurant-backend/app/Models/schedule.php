<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end'
    ];

    public function restaurant_infos()
    {
        return $this->belongsToOneOrZero(RestaurantInfo::class);
    }
}
