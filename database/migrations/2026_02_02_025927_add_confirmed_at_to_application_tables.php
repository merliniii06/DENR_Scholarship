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
            $table->timestamp('confirmed_at')->nullable()->after('status');
        });

        Schema::table('study_non_study', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable()->after('status');
        });

        Schema::table('permit_to_study', function (Blueprint $table) {
            $table->timestamp('confirmed_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('denr_scholar', function (Blueprint $table) {
            $table->dropColumn('confirmed_at');
        });

        Schema::table('study_non_study', function (Blueprint $table) {
            $table->dropColumn('confirmed_at');
        });

        Schema::table('permit_to_study', function (Blueprint $table) {
            $table->dropColumn('confirmed_at');
        });
    }
};
