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
        Schema::create('patient_analytics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('snapshot_date');
            $table->unsignedInteger('total_patients')->default(0);
            $table->unsignedInteger('new_patients')->default(0);
            $table->unsignedInteger('age_group_0_18')->default(0);
            $table->unsignedInteger('age_group_19_35')->default(0);
            $table->unsignedInteger('age_group_36_60')->default(0);
            $table->unsignedInteger('age_group_60_plus')->default(0);
            $table->string('top_disease_category')->nullable();
            $table->json('disease_category_counts')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_analytics');
    }
};
