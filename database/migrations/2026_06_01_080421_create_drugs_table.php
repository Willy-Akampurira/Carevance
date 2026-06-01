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
        Schema::create('drugs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('category_id')->nullable()->index('fk_drugs_category');
            $table->integer('quantity')->default(0);
            $table->string('unit', 50)->nullable();
            $table->boolean('reserved')->default(false);
            $table->date('expiry_date')->nullable();
            $table->integer('reorder_level')->default(10);
            $table->boolean('is_active')->nullable()->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drugs');
    }
};
