<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class offre extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['nom','description','prix',
    'image',
    'isDisponible'];
    public function plats()
    {
        return $this->belongsToMany(Plat::class,
        'offre_plat',
        'plat_id',
        'offre_id',
        'id',
        'id');
    }

    public function commandes()
    {
        return $this->belongsToMany(Commande::class,
            'offre_commande',
            'commande_id',
            'offre_id',
            'id',
            'id');
    }

}
