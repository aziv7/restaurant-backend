<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commande extends Model
{
    use HasFactory;
    use SoftDeletes;

    // if your key name is not 'id'
    // you can also set this to null if you don't have a primary key
    protected $primaryKey = 'commande_id';

    public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    protected $keyType = 'string';

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
        'livraison_address',
        'paiement_modality',
        'service_restaurant'
    ];

    public function code_reduction()
    {
        return $this->belongsTo(CodeReduction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function custom_offres()
    {
        return $this->belongsToMany(custom_offre::class,'commandes_custom_offres',
            'command_id','custom_offre_id','commande_id','id');
    }

    public function requested_plat()
    {
        return $this->belongsToMany(RequestedPlat::class, 'commande_requested_plats',
            'commande_id', 'requested_plat_id',
            'commande_id', 'id');
    }
}
