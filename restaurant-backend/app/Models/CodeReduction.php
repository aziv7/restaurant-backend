<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CodeReduction extends Model
{    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'code',
        'taux_reduction',
        'statut','date_expiration'
    ];
    public function Commandes()
    {
        return $this->hasMany(Commande::class);
    }
}
