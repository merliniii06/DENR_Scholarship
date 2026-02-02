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
        Schema::table('denr_scholar', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('file_8');
        });

        Schema::table('study_non_study', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('file_8');
        });

        Schema::table('permit_to_study', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('file_3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('denr_scholar', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('study_non_study', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('permit_to_study', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
