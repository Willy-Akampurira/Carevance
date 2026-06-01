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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appointment_id')->nullable()->index('prescriptions_appointment_id_foreign');
            $table->unsignedBigInteger('patient_id')->nullable()->index('prescriptions_patient_id_foreign');
            $table->unsignedBigInteger('drug_id')->nullable()->index('prescriptions_drug_id_foreign');
            $table->unsignedBigInteger('lot_id')->nullable()->index('fk_prescriptions_lot');
            $table->integer('quantity')->default(0);
            $table->string('unit', 50)->nullable();
            $table->string('dosage');
            $table->string('frequency');
            $table->integer('duration_days');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('issued_by');
            $table->enum('status', ['active', 'dispensed', 'missed', 'completed', 'expired', 'renewal_requested'])->default('active');
            $table->boolean('renewal_requested')->nullable()->default(false);
            $table->string('category', 100)->default('General');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
