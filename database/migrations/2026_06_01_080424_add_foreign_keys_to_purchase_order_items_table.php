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
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->foreign(['drug_id'], 'fk_po_items_drug')->references(['id'])->on('drugs')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['purchase_order_id'], 'fk_po_items_order')->references(['id'])->on('purchase_orders')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropForeign('fk_po_items_drug');
            $table->dropForeign('fk_po_items_order');
        });
    }
};
