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
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Pegawai yang melapor
            $table->string('title');
            $table->text('description');
            $table->enum('priority', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->enum('status', ['Reported', 'Reviewed', 'Assigned', 'Rejected'])->default('Reported');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
