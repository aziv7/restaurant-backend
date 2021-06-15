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
'ingredient',
'prix',
'quantite_supplement',
'token',
'longitude',
'latitude','plat_id','user_id',
        'code_reduction_id'];

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
        return $this->belongsTo(Plat::class);
    }
}