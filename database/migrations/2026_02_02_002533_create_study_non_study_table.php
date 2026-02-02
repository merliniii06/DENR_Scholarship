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
        Schema::create('study_non_study', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->unsignedTinyInteger('age');
            $table->string('gender');
            $table->string('email');
            $table->string('position');
            $table->string('office');
            $table->unsignedBigInteger('phone_number');
            $table->string('study_type'); // 'Study' or 'Non-Study'
            $table->string('file_1')->nullable();
            $table->string('file_2')->nullable();
            $table->string('file_3')->nullable();
            $table->string('file_4')->nullable();
            $table->string('file_5')->nullable();
            $table->string('file_6')->nullable();
            $table->string('file_7')->nullable();
            $table->string('file_8')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_non_study');
    }
};
