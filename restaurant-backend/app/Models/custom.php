<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class custom extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'nom',
        'prix'
    ];

    public function plats()
    {
        return $this->belongsToMany(Plat::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class,'ingredient_custom','custom_id',
            'ingredient_id','id','id');
    }
}
