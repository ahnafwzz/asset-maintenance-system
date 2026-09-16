<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $fillable = ['maintenance_request_id', 'asset_id', 'technician_id', 'status', 'resolution_notes', 'started_at', 'completed_at'];

    public function maintenanceRequest() {
        return $this->belongsTo(MaintenanceRequest::class);
    }

    public function asset() {
        return $this->belongsTo(Asset::class);
    }

    public function technician() {
        return $this->belongsTo(User::class, 'technician_id');
    }
}