<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Plat extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'nom',
        'prix',
        'description'
    ];

    public function categorie()
    {
        return $this->HasOneOrZero (Categorie::class);
    }

    public function Commande(){
        return $this->hasOne(Commande::class);
    }
}
