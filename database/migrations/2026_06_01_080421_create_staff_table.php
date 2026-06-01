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
        Schema::create('staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique('email');
            $table->string('phone', 50)->nullable();
            $table->unsignedBigInteger('department_id')->nullable()->index('fk_staff_department');
            $table->unsignedBigInteger('role_id')->index('fk_staff_role');
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
