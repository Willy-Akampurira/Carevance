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
        Schema::table('stock_lots', function (Blueprint $table) {
            $table->foreign(['category_id'], 'fk_stock_category')->references(['id'])->on('drug_categories')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['drug_id'], 'fk_stock_drug')->references(['id'])->on('drugs')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_lots', function (Blueprint $table) {
            $table->dropForeign('fk_stock_category');
            $table->dropForeign('fk_stock_drug');
        });
    }
};
