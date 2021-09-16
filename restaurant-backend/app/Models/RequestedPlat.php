<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestedPlat extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'nom',
        'prix',
        'description'
    ];


    public function Commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function custom_offres()
    {
        return $this->belongsToMany(custom_offre::class,'requested_plat_custom_offres',
            'requested_plat_id','custom_offre_id','id','id');
    }

    public function customs()
    {
        return $this->belongsToMany(custom::class,'requested_plats_custom',
            'requested_plats_id','custom_id','id','id');
    }
}
