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
        Schema::table('supplier_payments', function (Blueprint $table) {
            $table->foreign(['invoice_id'], 'fk_supplier_payments_invoice')->references(['id'])->on('supplier_invoices')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['supplier_id'], 'fk_supplier_payments_supplier')->references(['id'])->on('suppliers')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_payments', function (Blueprint $table) {
            $table->dropForeign('fk_supplier_payments_invoice');
            $table->dropForeign('fk_supplier_payments_supplier');
        });
    }
};
