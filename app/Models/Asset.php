<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'asset_code', 
        'name', 
        'asset_category_id', 
        'department_id', 
        'location_id', 
        'purchase_date', 
        'status', 
        'notes'
    ];

    // Relasi ke Kategori Aset
    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }

    // Relasi ke Departemen
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Relasi ke Lokasi
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
