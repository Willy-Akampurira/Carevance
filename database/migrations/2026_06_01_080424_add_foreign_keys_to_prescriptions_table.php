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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->foreign(['lot_id'], 'fk_prescriptions_lot')->references(['id'])->on('stock_lots')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['appointment_id'])->references(['id'])->on('appointments')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['drug_id'])->references(['id'])->on('drugs')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['patient_id'])->references(['id'])->on('patients')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropForeign('fk_prescriptions_lot');
            $table->dropForeign('prescriptions_appointment_id_foreign');
            $table->dropForeign('prescriptions_drug_id_foreign');
            $table->dropForeign('prescriptions_patient_id_foreign');
        });
    }
};
