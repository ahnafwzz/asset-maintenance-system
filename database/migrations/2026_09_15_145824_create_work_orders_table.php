<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            // nullable() karena ke depannya WO bisa dibuat dari Jadwal Berkala (Preventive), bukan cuma dari Request
            $table->foreignId('maintenance_request_id')->nullable()->constrained()->cascadeOnDelete(); 
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained('users')->cascadeOnDelete(); // Teknisi yang bertugas
            $table->enum('status', ['Assigned', 'In Progress', 'Resolved', 'Closed'])->default('Assigned');
            $table->text('resolution_notes')->nullable(); // Catatan perbaikan dari teknisi
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
