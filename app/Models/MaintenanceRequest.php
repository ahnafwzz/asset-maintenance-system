<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    protected $fillable = ['asset_id', 'user_id', 'technician_id', 'title', 'description', 'priority', 'status'];

    public function asset() {
        return $this->belongsTo(Asset::class);
    }

    public function user() {
        return $this->belongsTo(User::class); // Pelapor
    }

    // Tambahkan relasi ke Teknisi
    public function technician() {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function workOrder() {
        return $this->hasOne(WorkOrder::class);
    }
}