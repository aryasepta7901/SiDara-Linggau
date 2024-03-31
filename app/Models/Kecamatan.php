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
    public function EntryTBF()
    {
        return $this->hasMany(EntryTBF::class, 'kec_id');
    }
    public function EntryBST()
    {
        return $this->hasMany(EntryBST::class, 'kec_id');
    }
    public function EntryTH()
    {
        return $this->hasMany(EntryTH::class, 'kec_id');
    }
}
