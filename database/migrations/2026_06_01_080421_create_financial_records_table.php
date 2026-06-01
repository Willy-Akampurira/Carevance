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
        Schema::create('financial_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('patient_id')->index('fk_financial_records_patient');
            $table->string('invoice_number', 50);
            $table->date('invoice_date');
            $table->decimal('amount', 10);
            $table->enum('status', ['unpaid', 'paid', 'pending', 'cancelled'])->nullable()->default('unpaid');
            $table->string('insurance_provider')->nullable();
            $table->string('claim_number', 100)->nullable();
            $table->enum('claim_status', ['submitted', 'approved', 'denied', 'pending'])->nullable()->default('pending');
            $table->string('payment_method', 50)->nullable();
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};
