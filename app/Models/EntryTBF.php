<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryTBF extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'entrytbf';
    public $incrementing = false;
    protected $guarded = [];

    public function tbf()
    {
        return $this->hasMany(TBF::class, 'entry_id');
    }
    public function Kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kec_id');
    }
}
