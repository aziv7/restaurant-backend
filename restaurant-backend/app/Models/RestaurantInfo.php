<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RestaurantInfo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'rib',
        'secret_key_stripe',
        'public_key_stripe',
        'secret_key_paypal',
        'public_key_paypal',
        'nom_restaurant',
        'num_siret',
        'num_siren',
        'num_tva_intercommunautaire',
        'logo',
        'numero_tva',
        'address',
        'longitude',
        'latitude',
        'tel',
        'prixlivraison',
        'carte_bancaire',
        'cash',
        'livraison',
        'emporter',
        'sur_place',
        'facebook',
        'tik_tok',
        'instagram',
        'snapchat',
        'androidApp',
        'iosApp',
        'email'
    ];

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }

    public function schedules()
    {
        return $this->hasMany(schedule::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
