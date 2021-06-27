<?php

namespace App\Models;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nom',
        'prix',
        'quantite','type','stock','image'
    ];
    public function plats()
    {
        return $this->belongsToMany(Plat::class);
    }
    public function modificateur()
    {
        return $this->belongsTo(Modificateur::class);
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
