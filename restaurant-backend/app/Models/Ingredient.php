<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nom',
        'prix',
        'quantite','type','stock','image'
    ];
    public function plats()
    {
        return $this->belongsToMany(Plat::class)->withTimestamps();
    }
    public function modificateur()
    {
        return $this->belongsTo(Modificateur::class)->withTimestamps();
    }

}
