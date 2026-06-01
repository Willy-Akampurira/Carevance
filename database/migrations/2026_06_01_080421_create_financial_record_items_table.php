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
        Schema::create('financial_record_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('financial_record_id')->index('financial_record_id');
            $table->string('description');
            $table->integer('quantity')->nullable()->default(1);
            $table->decimal('unit_price', 10)->nullable()->default(0);
            $table->decimal('total', 10)->nullable()->storedAs('`quantity` * `unit_price`');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_record_items');
    }
};
