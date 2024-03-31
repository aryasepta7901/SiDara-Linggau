<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TBF extends Model
{
    use HasFactory;
    protected $table = 'tbf';

    protected $guarded = [];

    public function entryTBF()
    {
        return $this->belongsTo(EntryTBF::class, 'entry_id');
    }
    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }
}
