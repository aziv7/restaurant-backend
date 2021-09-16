<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class custom_offre extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'nom',
        'prix'
    ];

    public function commandes()
    {
        return $this->belongsToMany(Commande::class);
    }

    public function requested_plats()
    {
        return $this->belongsToMany(RequestedPlat::class, 'custom_offre_requested_plats',
            'custom_offre_id', 'requested_plat_id',
            'id', 'id');
    }
}
