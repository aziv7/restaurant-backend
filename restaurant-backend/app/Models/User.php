<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Commande;
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'prenom',
        'date de naissance',
        'email',
        'numero de telephone',
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
        return $this->belongsToMany(Role::class,'role_users');
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
}
