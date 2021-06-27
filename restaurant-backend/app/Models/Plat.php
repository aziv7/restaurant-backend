<?php

namespace App\Models;

use DateTimeInterface;
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
        'description'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function categorie()
    {
        return $this->HasOneOrZero (Categorie::class);
    }

    public function images()
    {
        return $this->HasMany (Image::class);
    }

    public function modificateurs()
    {
        return $this->belongsToMany(Modificateur::class);
    }

    public function Commandes()
    {
        return $this->hasMany(Commande::class);
    }
    public function supplements()
    {
        return $this->HasMany (Supplement::class);
    }
}
