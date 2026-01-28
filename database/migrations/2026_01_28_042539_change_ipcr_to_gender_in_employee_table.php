<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column exists before renaming
        if (Schema::hasColumn('employee', 'ipcr')) {
            DB::statement('ALTER TABLE `employee` CHANGE `ipcr` `gender` VARCHAR(255) NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if column exists before renaming back
        if (Schema::hasColumn('employee', 'gender')) {
            DB::statement('ALTER TABLE `employee` CHANGE `gender` `ipcr` VARCHAR(255) NOT NULL');
        }
    }
};
