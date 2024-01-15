<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanaman extends Model
{
    use HasFactory;
    protected $table = 'tanaman';
    public $incrementing = false;

    protected $guarded = [];
    public function sbs()
    {
        return $this->hasMany(sbs::class, 'tanaman_id');
    }
}
