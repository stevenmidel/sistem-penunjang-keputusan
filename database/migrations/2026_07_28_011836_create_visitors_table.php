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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();

            // Nama pengunjung
            $table->string('nama', 100);

            // Token unik browser
            $table->uuid('visitor_token')->unique();

            // Total kunjungan
            $table->unsignedInteger('visit_count')->default(1);

            // Kunjungan pertama
            $table->timestamp('first_visit');

            // Kunjungan terakhir
            $table->timestamp('last_visit');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
