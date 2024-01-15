<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;
    protected $table = 'kecamatan';
    protected $guarded = [];

    public function user()
    {
        return $this->hasMany(User::class, 'kec_id');
    }
    public function EntrySBS()
    {
        return $this->hasMany(EntrySBS::class, 'kec_id');
    }
}
