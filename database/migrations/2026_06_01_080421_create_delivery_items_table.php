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
        Schema::create('delivery_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('delivery_id')->index('fk_delivery_items_delivery');
            $table->unsignedBigInteger('drug_id')->nullable()->index('fk_delivery_items_drug');
            $table->string('batch_number', 100)->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('quantity_received')->default(0);
            $table->decimal('unit_cost', 12)->default(0);
            $table->decimal('line_total', 12)->nullable()->storedAs('`quantity_received` * `unit_cost`');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_items');
    }
};
