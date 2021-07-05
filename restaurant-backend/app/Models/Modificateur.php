<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modificateur extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'nom',
    ];

    public function plats()
    {
        return $this->belongsToMany(Plat::class);
    }

    public function ingredients()
    {
        return $this->belongsToMany(
            Ingredient::class,
            'ingredient_modificateur',
            'modificateur_id',
            'ingredient_id',
            'id',
            'id'
        );
    }
}
