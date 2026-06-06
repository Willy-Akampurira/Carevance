<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Replaces global unique email constraints on patients, staff, and suppliers
     * with composite (tenant_id, email) unique constraints.
     *
     * Rationale: In a multi-tenant system, the same patient/staff email address
     * can legitimately exist across different clinics (tenants). A global unique
     * constraint would incorrectly reject valid registrations from different tenants.
     */
    public function up(): void
    {
        // ── patients ─────────────────────────────────────────────────────────
        Schema::table('patients', function (Blueprint $table) {
            // Drop the old global unique constraint
            $table->dropUnique(['email']);

            // Add tenant-scoped unique constraint: same email allowed across tenants
            $table->unique(['tenant_id', 'email'], 'uq_patients_tenant_email');
        });

        // ── staff ─────────────────────────────────────────────────────────────
        Schema::table('staff', function (Blueprint $table) {
            $table->dropUnique('email');

            $table->unique(['tenant_id', 'email'], 'uq_staff_tenant_email');
        });

        // ── suppliers ─────────────────────────────────────────────────────────
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['email']);

            $table->unique(['tenant_id', 'email'], 'uq_suppliers_tenant_email');
        });

        // ── users ─────────────────────────────────────────────────────────────
        // The users table email unique constraint is kept GLOBAL because:
        //   1) Users authenticate across the whole platform (they log in via subdomain,
        //      but their account is unique platform-wide)
        //   2) A single person should not have two accounts on the same platform
        // DO NOT scope users.email by tenant.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropUnique('uq_patients_tenant_email');
            $table->unique('email');
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->dropUnique('uq_staff_tenant_email');
            $table->unique('email');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique('uq_suppliers_tenant_email');
            $table->unique('email');
        });
    }
};
