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
        Schema::create('stock_lots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('drug_id')->index('fk_stock_drug');
            $table->string('name');
            $table->unsignedBigInteger('category_id')->nullable()->index('fk_stock_category');
            $table->text('description')->nullable();
            $table->string('unit', 50)->nullable();
            $table->integer('quantity');
            $table->boolean('reserved')->nullable()->default(false);
            $table->integer('reorder_level')->nullable()->default(0);
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['new', 'old'])->nullable()->default('new');
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_lots');
    }
};
