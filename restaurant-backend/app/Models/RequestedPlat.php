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

    public function customs()
    {
        return $this->belongsToMany(custom::class,'requested_plats_custom',
            'custom_id','requested_plats_id','id','id');
    }
}
