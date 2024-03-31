<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TH extends Model
{
    use HasFactory;
    protected $table = 'th';

    protected $guarded = [];

    public function entryTH()
    {
        return $this->belongsTo(EntryTH::class, 'entry_id');
    }
    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }
}
