<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'nom',
        'src',
        'plat_id',
        'user_id'
    ];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function plat()
    {
        return $this->belongsTo(Plat::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
