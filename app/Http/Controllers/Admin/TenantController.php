<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::withCount('users')->latest()->get();
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('admin.tenants.create');
    }

    public function store(\App\Http\Requests\StoreTenantRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Create Tenant
            $tenant = Tenant::create([
                'name' => $validated['name'],
                'domain' => $validated['domain'] ?? Str::slug($validated['name']) . '.' . parse_url(config('app.url'), PHP_URL_HOST),
                'status' => 'active',
                'api_key' => Str::random(32),
            ]);

            // Create Initial Admin User for Tenant
            $user = User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'tenant_id' => $tenant->id,
            ]);


            // Assign role (assuming Spatie permissions is set up)
            if (class_exists(\Spatie\Permission\Models\Role::class)) {
                $user->assignRole('admin');
            }

            // Create Wallet for the admin user
            $user->wallet()->create([
                'tenant_id' => $tenant->id,
            ]);

            DB::commit();

            return redirect()->route('admin.tenants.index')->with('status', 'success')->with('message', 'Tenant and initial admin user created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('status', 'error')->with('message', 'Failed to create tenant: ' . $e->getMessage());
        }
    }
}

