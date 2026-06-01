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
        Schema::table('delivery_items', function (Blueprint $table) {
            $table->foreign(['delivery_id'], 'fk_delivery_items_delivery')->references(['id'])->on('deliveries')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['drug_id'], 'fk_delivery_items_drug')->references(['id'])->on('drugs')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_items', function (Blueprint $table) {
            $table->dropForeign('fk_delivery_items_delivery');
            $table->dropForeign('fk_delivery_items_drug');
        });
    }
};
