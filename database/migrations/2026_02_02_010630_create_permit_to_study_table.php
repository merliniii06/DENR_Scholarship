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
        Schema::create('permit_to_study', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->unsignedTinyInteger('age');
            $table->string('gender');
            $table->string('email');
            $table->string('position');
            $table->string('office');
            $table->unsignedBigInteger('phone_number');
            $table->string('file_1')->nullable(); // Request Letter
            $table->string('file_2')->nullable(); // IPCR
            $table->string('file_3')->nullable(); // Registration Form from School
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permit_to_study');
    }
};
