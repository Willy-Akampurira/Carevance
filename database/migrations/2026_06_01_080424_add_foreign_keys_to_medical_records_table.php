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
        Schema::table('medical_records', function (Blueprint $table) {
            $table->foreign(['appointment_id'], 'fk_medical_records_appointment')->references(['id'])->on('appointments')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['patient_id'], 'fk_medical_records_patient')->references(['id'])->on('patients')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropForeign('fk_medical_records_appointment');
            $table->dropForeign('fk_medical_records_patient');
        });
    }
};
