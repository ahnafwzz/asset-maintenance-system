<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    // Memberi tahu Laravel bahwa 1 Departemen bisa punya banyak Aset
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}