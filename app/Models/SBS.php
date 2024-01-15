<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SBS extends Model
{
    use HasFactory;
    protected $table = 'sbs';

    protected $guarded = [];

    public function entrySBS()
    {
        return $this->belongsTo(EntrySBS::class, 'entry_id');
    }
    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }
}
