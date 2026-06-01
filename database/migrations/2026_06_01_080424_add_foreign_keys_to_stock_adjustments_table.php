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
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->foreign(['drug_id'], 'fk_adjustment_drug')->references(['id'])->on('drugs')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['stock_lot_id'], 'fk_adjustment_lot')->references(['id'])->on('stock_lots')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'], 'fk_adjustment_user')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->dropForeign('fk_adjustment_drug');
            $table->dropForeign('fk_adjustment_lot');
            $table->dropForeign('fk_adjustment_user');
        });
    }
};
