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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_id')->index('fk_medical_records_patient');
            $table->unsignedBigInteger('appointment_id')->nullable()->index('fk_medical_records_appointment');
            $table->string('diagnosis');
            $table->text('lab_results')->nullable();
            $table->text('imaging_results')->nullable();
            $table->text('allergies')->nullable();
            $table->text('notes')->nullable();
            $table->string('recorded_by');
            $table->enum('status', ['active', 'archived'])->nullable()->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
