<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'parent_id'];

    // Relasi ke lokasi induk (parent)
    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }
}
