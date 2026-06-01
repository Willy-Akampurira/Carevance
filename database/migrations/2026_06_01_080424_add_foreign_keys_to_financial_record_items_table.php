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
        Schema::table('financial_record_items', function (Blueprint $table) {
            $table->foreign(['financial_record_id'], 'financial_record_items_ibfk_1')->references(['id'])->on('financial_records')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_record_items', function (Blueprint $table) {
            $table->dropForeign('financial_record_items_ibfk_1');
        });
    }
};
