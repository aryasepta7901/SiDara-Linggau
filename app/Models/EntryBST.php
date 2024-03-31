<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryBST extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'entrybst';
    public $incrementing = false;
    protected $guarded = [];

    public function bst()
    {
        return $this->hasMany(BST::class, 'entry_id');
    }
    public function Kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kec_id');
    }
}
