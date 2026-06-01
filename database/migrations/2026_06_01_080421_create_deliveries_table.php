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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_id')->index('fk_deliveries_supplier');
            $table->unsignedBigInteger('purchase_order_id')->nullable()->index('fk_deliveries_po');
            $table->string('delivery_number', 100)->unique('delivery_number');
            $table->date('delivery_date');
            $table->enum('status', ['pending', 'received', 'partially_received', 'cancelled'])->nullable()->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
