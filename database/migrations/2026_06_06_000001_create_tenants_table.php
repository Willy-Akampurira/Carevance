<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the `tenants` table — the top-level entity for each Clinic in the platform.
     * In the UI, tenants are referred to as "Clinics". In the backend, the word "tenant" is used.
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();

            // Identity
            $table->string('name');                                                       // "City Health Clinic"
            $table->string('subdomain')->unique();                                        // "cityhealth" → cityhealth.domain.com
            $table->string('email')->unique();                                            // Primary contact / billing email
            $table->string('phone')->nullable();

            // Plan & Subscription
            $table->string('plan')->default('basic');                                     // basic | professional | enterprise
            $table->enum('status', ['trial', 'active', 'suspended', 'cancelled'])
                  ->default('trial');
            $table->timestamp('trial_ends_at')->nullable();                              // Trial expiry
            $table->timestamp('subscription_ends_at')->nullable();                       // Paid plan expiry

            // Flexible metadata (logo URL, timezone, currency, etc.)
            $table->json('meta')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
