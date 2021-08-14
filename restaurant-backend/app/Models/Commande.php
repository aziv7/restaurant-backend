<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commande extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'livraison',
        'status',
        'date_paiement',
        'date_traitement',
        'token',
        'longitude',
        'latitude',
        'user_id',
        'code_reduction_id',
        'created_at',
        'updated_at',
        'prix_total',
        'livraison_address'
    ];

    public function code_reduction()
    {
        return $this->belongsTo(CodeReduction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plat()
    {
        return $this->belongsToMany(Plat::class, 'commande_plats',
            'commande_id', 'plat_id',
            'commande_id', 'id');
    }

    public function Offres()
    {
        return $this->belongsToMany(Offre::class,
            'offre_commande',
            'commande_id',
            'offre_id',
            'id',
            'id');
    }
}
