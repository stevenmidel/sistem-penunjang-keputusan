<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('unit_usahas', function (Blueprint $table) {

            $table->decimal('harga', 10, 2)
                ->nullable()
                ->change();

            $table->text('jenis_layanan')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('unit_usahas', function (Blueprint $table) {

            $table->decimal('harga', 10, 2)
                ->nullable(false)
                ->change();

            $table->text('jenis_layanan')
                ->nullable(false)
                ->change();
        });
    }
};
