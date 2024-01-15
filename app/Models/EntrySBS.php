<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrySBS extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'entrysbs';
    protected $guarded = [];

    public function sbs()
    {
        return $this->hasMany(sbs::class, 'entry_id');
    }
    public function Kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kec_id');
    }
}
