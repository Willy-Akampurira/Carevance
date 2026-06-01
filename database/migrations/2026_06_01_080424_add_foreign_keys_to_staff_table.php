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
        Schema::table('staff', function (Blueprint $table) {
            $table->foreign(['department_id'], 'fk_staff_department')->references(['id'])->on('departments')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['role_id'], 'fk_staff_role')->references(['id'])->on('roles')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropForeign('fk_staff_department');
            $table->dropForeign('fk_staff_role');
        });
    }
};
