<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'note',

    ];
    public function plat()
    {
        return $this->hasOne(Plat::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
