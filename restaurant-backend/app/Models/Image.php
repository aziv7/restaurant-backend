<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'nom',
        'src','plat_id'
    ];

    public function plat()
    {
        return $this->belongsTo(Plat::class);
    }
}
