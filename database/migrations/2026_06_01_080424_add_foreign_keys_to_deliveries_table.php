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
        Schema::table('deliveries', function (Blueprint $table) {
            $table->foreign(['purchase_order_id'], 'fk_deliveries_po')->references(['id'])->on('purchase_orders')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['supplier_id'], 'fk_deliveries_supplier')->references(['id'])->on('suppliers')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropForeign('fk_deliveries_po');
            $table->dropForeign('fk_deliveries_supplier');
        });
    }
};
