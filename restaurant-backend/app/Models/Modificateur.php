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
        return $this->hasMany(Ingredient::class);
    }
    public function supplements()
    {
        return $this->belongsToMany(Supplement::class, 'modificateur_supplement');
    }
}
