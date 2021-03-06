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
        'quantite',
        'type',
    ];

    public function modificateurs()
    {
        return $this->belongsToMany(Modificateur::class);
    }
    public function customs()
    {
        return $this->belongsToMany(custom::class);
    }

}
