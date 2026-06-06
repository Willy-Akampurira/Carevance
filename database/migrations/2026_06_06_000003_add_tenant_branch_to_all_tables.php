<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds `tenant_id` and `branch_id` columns to every operational table in the application.
     * Columns are nullable initially to preserve existing data. After backfilling existing rows
     * with a default tenant and branch, run a follow-up migration to make them non-nullable.
     *
     * Tables scoped by BOTH tenant_id AND branch_id (27 tables):
     *   users, patients, staff, drugs, drug_categories, stock_lots, stock_adjustments,
     *   suppliers, purchase_orders, purchase_order_items, supplier_invoices, supplier_payments,
     *   deliveries, delivery_items, appointments, prescriptions, medical_records,
     *   financial_records, financial_record_items, payments, departments, shifts,
     *   attendances, activity_logs, performance_reports, patient_analytics, settings, orders
     *
     * Tables intentionally NOT scoped (Spatie permission tables — global by design):
     *   roles, permissions, model_has_roles, model_has_permissions, role_has_permissions
     */
    public function up(): void
    {
        $tables = [
            'users',
            'patients',
            'staff',
            'drugs',
            'drug_categories',
            'stock_lots',
            'stock_adjustments',
            'suppliers',
            'purchase_orders',
            'purchase_order_items',
            'supplier_invoices',
            'supplier_payments',
            'deliveries',
            'delivery_items',
            'appointments',
            'prescriptions',
            'medical_records',
            'financial_records',
            'financial_record_items',
            'payments',
            'departments',
            'shifts',
            'attendances',
            'activity_logs',
            'performance_reports',
            'patient_analytics',
            'settings',
            'orders',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                // Add tenant_id after id
                $table->foreignId('tenant_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('tenants')
                      ->nullOnDelete();

                // Add branch_id after tenant_id
                $table->foreignId('branch_id')
                      ->nullable()
                      ->after('tenant_id')
                      ->constrained('branches')
                      ->nullOnDelete();

                // Composite index for performant tenant+branch queries
                $table->index(['tenant_id', 'branch_id'], "idx_{$tableName}_tenant_branch");
            });
        }

        // --- BACKFILL EXISTING DATA ---
        // 1. Create a default tenant if one doesn't exist yet
        $defaultTenantId = Illuminate\Support\Facades\DB::table('tenants')
            ->where('subdomain', 'default')
            ->value('id');

        if (!$defaultTenantId) {
            $defaultTenantId = Illuminate\Support\Facades\DB::table('tenants')->insertGetId([
                'name' => 'Default Clinic',
                'subdomain' => 'default',
                'email' => 'admin@carevance.com',
                'plan' => 'basic',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // 2. Create a default branch if none exists
        $defaultBranchId = Illuminate\Support\Facades\DB::table('branches')
            ->where('tenant_id', $defaultTenantId)
            ->where('is_primary', true)
            ->value('id');

        if (!$defaultBranchId) {
            $defaultBranchId = Illuminate\Support\Facades\DB::table('branches')->insertGetId([
                'tenant_id' => $defaultTenantId,
                'name' => 'Main Branch',
                'is_primary' => true,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        // 3. Backfill all tables to map existing records to the default tenant/branch
        foreach ($tables as $tableName) {
            Illuminate\Support\Facades\DB::table($tableName)
                ->whereNull('tenant_id')
                ->update([
                    'tenant_id' => $defaultTenantId,
                    'branch_id' => $defaultBranchId,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users', 'patients', 'staff', 'drugs', 'drug_categories',
            'stock_lots', 'stock_adjustments', 'suppliers', 'purchase_orders',
            'purchase_order_items', 'supplier_invoices', 'supplier_payments',
            'deliveries', 'delivery_items', 'appointments', 'prescriptions',
            'medical_records', 'financial_records', 'financial_record_items',
            'payments', 'departments', 'shifts', 'attendances',
            'activity_logs', 'performance_reports', 'patient_analytics',
            'settings', 'orders',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropIndex("idx_{$tableName}_tenant_branch");
                $table->dropConstrainedForeignId('branch_id');
                $table->dropConstrainedForeignId('tenant_id');
            });
        }
    }
};
