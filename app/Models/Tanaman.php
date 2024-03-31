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
    public function tbf()
    {
        return $this->hasMany(tbf::class, 'tanaman_id');
    }
    public function bst()
    {
        return $this->hasMany(bst::class, 'tanaman_id');
    }
    public function th()
    {
        return $this->hasMany(th::class, 'tanaman_id');
    }
}
