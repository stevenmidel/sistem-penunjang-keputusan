<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE unit_usahas 
            MODIFY jenis_layanan ENUM(
                'Kiloan',
                'Satuan',
                'Express Komplit',
                'Express Hanya Cuci',
                'Cuci Saja',
                'Cuci Setrika'
            )
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE unit_usahas 
            MODIFY jenis_layanan ENUM(
                'Kiloan',
                'Satuan'
            )
        ");
    }
};
