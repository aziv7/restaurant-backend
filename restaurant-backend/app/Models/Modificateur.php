<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modificateur extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'nom',
    ];
    
    public function modificateur()
    {
        return $this->hasMany(Plat::class)->withTimestamps();
    }
}
