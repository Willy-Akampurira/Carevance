<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SuperadminController extends Controller
{
    /**
     * Display the superadmin dashboard.
     */
    public function dashboard()
    {
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::whereIn('status', ['active', 'trial'])->count();
        $suspendedTenants = Tenant::where('status', 'suspended')->count();
        $totalBranches = Branch::count();

        // Recently created tenants
        $recentTenants = Tenant::orderBy('created_at', 'desc')->take(5)->get();

        return view('superadmin.dashboard', compact(
            'totalTenants',
            'activeTenants',
            'suspendedTenants',
            'totalBranches',
            'recentTenants'
        ));
    }

    /**
     * List all tenants.
     */
    public function index()
    {
        $tenants = Tenant::withCount(['branches', 'users'])->get();
        return view('superadmin.tenants.index', compact('tenants'));
    }

    /**
     * Show form to provision a new tenant clinic.
     */
    public function create()
    {
        return view('superadmin.tenants.create');
    }

    /**
     * Store and provision a new tenant clinic.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'subdomain'       => [
                'required',
                'string',
                'alpha_dash',
                'max:50',
                'unique:tenants,subdomain',
                function ($attribute, $value, $fail) {
                    $reserved = ['www', 'superadmin', 'landing', 'api', 'admin', 'default', 'carevance'];
                    if (in_array(strtolower($value), $reserved)) {
                        $fail("The subdomain '{$value}' is reserved for platform use.");
                    }
                }
            ],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:tenants,email'],
            'plan'           => ['required', 'in:basic,professional,enterprise'],
            'admin_name'     => ['required', 'string', 'max:255'],
            'admin_email'    => ['required', 'string', 'email', 'max:255'],
            'admin_password' => ['required', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create Tenant Clinic
            $tenant = Tenant::create([
                'name'                 => $request->name,
                'subdomain'            => strtolower($request->subdomain),
                'email'                => $request->email,
                'plan'                 => $request->plan,
                'status'               => 'trial',
                'trial_ends_at'        => now()->addDays(14),
            ]);

            // 2. Create primary Branch
            $branch = Branch::create([
                'tenant_id'  => $tenant->id,
                'name'       => 'Main Branch',
                'is_primary' => true,
                'status'     => 'active',
            ]);

            // 3. Create Admin user for this tenant and assign Role
            // Temporarily bypass query scopes during user creation to prevent context issues
            $adminUser = User::create([
                'tenant_id' => $tenant->id,
                'branch_id' => $branch->id,
                'name'      => $request->admin_name,
                'email'     => $request->admin_email,
                'password'  => bcrypt($request->admin_password),
                'is_active' => true,
            ]);

            $adminRole = Role::firstOrCreate(['name' => 'admin']);
            $adminUser->assignRole($adminRole);
        });

        return redirect()->route('superadmin.tenants.index')
            ->with('success', "Clinic '{$request->name}' provisioned successfully on subdomain '{$request->subdomain}.carevance.test'!");
    }

    /**
     * Show detail of a single tenant clinic.
     */
    public function show($id)
    {
        $tenant = Tenant::findOrFail($id);

        // Fetch details of this tenant by explicitly removing Global Scopes
        $branches = Branch::withoutGlobalScopes()->where('tenant_id', $tenant->id)->get();
        $users = User::withoutGlobalScopes()->where('tenant_id', $tenant->id)->get();

        return view('superadmin.tenants.show', compact('tenant', 'branches', 'users'));
    }

    /**
     * Toggle tenant status.
     */
    public function toggleStatus(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        $request->validate([
            'status' => ['required', 'in:trial,active,suspended,cancelled'],
        ]);

        $tenant->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', "Tenant status updated to '{$request->status}' successfully.");
    }
}
