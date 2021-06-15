<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "roles";
    protected $fillable = [
        'Nom_des_roles'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class,'role_users');
    }
}
