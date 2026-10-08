<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Tambahkan role pengawas_laundry
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'super_admin',
                'admin_laundry',
                'pengawas_laundry',
                'user'
            )
            NOT NULL
            DEFAULT 'admin_laundry'
        ");

        /*
        |--------------------------------------------------------------------------
        | Tambahkan pengawas_id
        |--------------------------------------------------------------------------
        |
        | Kolom ini menyimpan ID user yang berperan sebagai
        | Pengawas Laundry untuk Admin Laundry tersebut.
        |
        */

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('pengawas_id')
                ->nullable()
                ->after('role')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Hapus pengawas_id
        |--------------------------------------------------------------------------
        */

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pengawas_id']);
            $table->dropColumn('pengawas_id');
        });

        /*
        |--------------------------------------------------------------------------
        | Kembalikan struktur role sebelumnya
        |--------------------------------------------------------------------------
        */

        DB::statement("
            ALTER TABLE users
            MODIFY role ENUM(
                'super_admin',
                'admin_laundry',
                'user'
            )
            NOT NULL
            DEFAULT 'admin_laundry'
        ");
    }
};
