<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commande extends Model
{    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
'livraison',
'status',
'plat',
'quantite',
'date_paiement',
'date_traitement',
'supprime',
'ingredient',
'prix',
'quantite_supplement',
'token',
'longitude',
'latitude','plat_id','user_id'];

}
