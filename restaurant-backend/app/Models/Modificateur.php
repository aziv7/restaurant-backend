<?php

namespace App\Models;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modificateur extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'nom',
    ];

    public function plats()
    {
        return $this->belongsToMany(Plat::class);
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}
