<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryTH extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'entryth';
    public $incrementing = false;
    protected $guarded = [];

    public function th()
    {
        return $this->hasMany(TH::class, 'entry_id');
    }
    public function Kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kec_id');
    }
}
