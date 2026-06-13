<?php

use App\Models\Tenant;
use App\Models\Branch;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Set base URL to match local domain setup
    Config::set('app.url', 'http://carevance.test');
});

test('tenant middleware resolves correct tenant from subdomain', function () {
    $tenant = Tenant::create([
        'name' => 'Clinic A',
        'subdomain' => 'clinica',
        'email' => 'clinica@example.com',
        'status' => 'active',
    ]);

    // Perform request on the subdomain
    $response = $this->get('http://clinica.carevance.test/');

    $response->assertOk(); // The root page should render without errors
    expect(app()->bound('currentTenant'))->toBeTrue();
    expect(app('currentTenant')->id)->toEqual($tenant->id);
});

test('models automatically scope queries and creations to the resolved tenant', function () {
    // 1. Create Tenant A
    $tenantA = Tenant::create([
        'name' => 'Clinic A',
        'subdomain' => 'clinica',
        'email' => 'clinica@example.com',
        'status' => 'active',
    ]);

    $branchA = Branch::create([
        'tenant_id' => $tenantA->id,
        'name' => 'Clinic A Main Branch',
        'is_primary' => true,
        'status' => 'active',
    ]);

    // 2. Create Tenant B
    $tenantB = Tenant::create([
        'name' => 'Clinic B',
        'subdomain' => 'clinicb',
        'email' => 'clinicb@example.com',
        'status' => 'active',
    ]);

    $branchB = Branch::create([
        'tenant_id' => $tenantB->id,
        'name' => 'Clinic B Main Branch',
        'is_primary' => true,
        'status' => 'active',
    ]);

    // 3. Set context to Tenant A and create a Patient
    app()->instance('currentTenant', $tenantA);
    app()->instance('currentBranch', $branchA);

    $patientA = Patient::create([
        'name' => 'John Doe',
        'gender' => 'male',
        'dob' => '1990-01-01',
        'contact' => '1234567890',
        'address' => '123 Street A',
    ]);

    expect($patientA->tenant_id)->toEqual($tenantA->id);
    expect($patientA->branch_id)->toEqual($branchA->id);

    // 4. Set context to Tenant B and create a Patient
    app()->instance('currentTenant', $tenantB);
    app()->instance('currentBranch', $branchB);

    $patientB = Patient::create([
        'name' => 'Jane Smith',
        'gender' => 'female',
        'dob' => '1992-02-02',
        'contact' => '0987654321',
        'address' => '456 Street B',
    ]);

    expect($patientB->tenant_id)->toEqual($tenantB->id);
    expect($patientB->branch_id)->toEqual($branchB->id);

    // 5. Verify query scoping
    // Under Tenant B context, only Patient B should be returned
    $patients = Patient::all();
    expect($patients->count())->toEqual(1);
    expect($patients->first()->id)->toEqual($patientB->id);

    // Under Tenant A context, only Patient A should be returned
    app()->instance('currentTenant', $tenantA);
    app()->instance('currentBranch', $branchA);

    $patients = Patient::all();
    expect($patients->count())->toEqual(1);
    expect($patients->first()->id)->toEqual($patientA->id);
});
