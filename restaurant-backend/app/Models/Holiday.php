<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'holiday'
    ];

    public function restaurant_infos()
    {
        return $this->belongsToOneOrZero(RestaurantInfo::class);
    }
}
