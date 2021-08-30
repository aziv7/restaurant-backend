<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plat extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'nom',
        'prix',
        'description',
        'image'
    ];

    public function categorie()
    {
        return $this->belongsToOneOrZero(Categorie::class);
    }

    public function images()
    {
        return $this->HasMany(Image::class);
    }

    public function modificateurs()
    {
        return $this->belongsToMany(Modificateur::class, 'modificateur_plat',
            'plat_id','modificateur_id','id','id');
    }

    public function Commandes()
    {
        return $this->hasMany(Commande::class);
    }
    public function supplements()
    {
        return $this->belongsToMany(Supplement::class);
    }

    public function ratings()
    {
        return $this->belongsToMany(Rating::class);
    }

    public function customs()
    {
        return $this->belongsToMany(custom::class,'plat_custom',
            'plat_id','custom_id','id','id');
    }

    public function Offres()
    {
        return $this->belongsToMany(Offre::class);
    }
}
