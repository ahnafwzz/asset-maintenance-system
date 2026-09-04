<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique(); // Kode unik aset (misal: INV-2026-001)
            $table->string('name'); // Nama aset
            
            // Relasi ke Master Data
            $table->foreignId('asset_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            
            $table->date('purchase_date')->nullable(); // Tanggal pembelian
            $table->enum('status', ['active', 'maintenance', 'broken', 'retired'])->default('active'); // Status kondisi aset
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
