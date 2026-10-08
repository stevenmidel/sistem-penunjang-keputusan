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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | User yang melakukan aktivitas
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Jenis aktivitas
            |--------------------------------------------------------------------------
            |
            | Contoh:
            | CREATE
            | UPDATE
            | DELETE
            | VIEW
            |
            */

            $table->string('aksi');

            /*
            |--------------------------------------------------------------------------
            | Modul yang digunakan
            |--------------------------------------------------------------------------
            |
            | Contoh:
            | Unit Usaha
            | Layanan
            |
            */

            $table->string('modul');

            /*
            |--------------------------------------------------------------------------
            | ID data yang terkena aktivitas
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('data_id')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Deskripsi aktivitas
            |--------------------------------------------------------------------------
            */

            $table->text('deskripsi');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */

            $table->index('user_id');
            $table->index('aksi');
            $table->index('modul');
            $table->index('data_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
