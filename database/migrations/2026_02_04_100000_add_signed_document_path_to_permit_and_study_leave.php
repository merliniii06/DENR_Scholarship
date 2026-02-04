<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permit_to_study', function (Blueprint $table) {
            $table->string('signed_document_path')->nullable()->after('signed_document_sent_at');
        });
        Schema::table('study_leave', function (Blueprint $table) {
            $table->string('signed_document_path')->nullable()->after('signed_document_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('permit_to_study', function (Blueprint $table) {
            $table->dropColumn('signed_document_path');
        });
        Schema::table('study_leave', function (Blueprint $table) {
            $table->dropColumn('signed_document_path');
        });
    }
};
