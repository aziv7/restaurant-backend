<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Commande;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;
    use HasApiTokens;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'prenom',
        'date_de_naissance',
        'email',
        'numero_de_telephone',
        'is_connected'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //user has many roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_users');
    }

    //user has one coordonneesAuthentification
    public function coordonneesAuthentification()
    {
        return $this->HasOne(CoordonneesAuthentification::class);
    }

    public function Commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function Code_reductions()
    {
        return $this->hasMany(CodeReduction::class);
    }

    public function img()
    {
        return $this->hasOne(Image::class);
    }

    public function ratings()
    {
        return $this->belongsToMany(Rating::class);
    }

    public function Code_reset()
    {
        return $this->belongsToMany(ResetCode::class);
    }

    public function restaurant_infos()
    {
        return $this->belongsToMany(RestaurantInfo::class);
    }
}
