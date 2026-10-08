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
        Schema::create('layanan_laundry', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unit_usaha_id')
                ->constrained('unit_usahas')
                ->cascadeOnDelete();

            $table->string('nama_layanan');

            $table->decimal('harga', 10, 2);

            $table->string('estimasi')->nullable();

            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanan_laundry');
    }
};
