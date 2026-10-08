<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('unit_usahas', function (Blueprint $table) {
            $table->json('jenis_layanan')->change();
        });
    }

    public function down(): void
    {
        Schema::table('unit_usahas', function (Blueprint $table) {
            $table->string('jenis_layanan')->change();
        });
    }
};
