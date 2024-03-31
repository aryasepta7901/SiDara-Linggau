<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BST extends Model
{
    use HasFactory;
    protected $table = 'bst';

    protected $guarded = [];

    public function EntryBST()
    {
        return $this->belongsTo(EntryBST::class, 'entry_id');
    }
    public function tanaman()
    {
        return $this->belongsTo(Tanaman::class, 'tanaman_id');
    }
}
