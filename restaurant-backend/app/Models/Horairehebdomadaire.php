<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Horairehebdomadaire extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'jour','heure_debut','heure_fermeture'
    ];

}
