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
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign(['shift_id'], 'fk_attendance_shift')->references(['id'])->on('shifts')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['staff_id'], 'fk_attendance_staff')->references(['id'])->on('staff')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign('fk_attendance_shift');
            $table->dropForeign('fk_attendance_staff');
        });
    }
};
