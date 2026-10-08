<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    protected $signature = 'db:create {name}';
    protected $description = 'Create MySQL database';

    public function handle()
    {
        $dbName = $this->argument('name');

        $charset = config('database.connections.mysql.charset');
        $collation = config('database.connections.mysql.collation');

        DB::statement(
            "CREATE DATABASE IF NOT EXISTS `$dbName`
            CHARACTER SET $charset
            COLLATE $collation"
        );

        $this->info("Database `$dbName` berhasil dibuat.");
    }
}