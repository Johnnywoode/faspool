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
                'name'    => $validated['name'],
                'domain'  => $validated['domain'] ?? Str::slug($validated['name']) . '.' . parse_url(config('app.url'), PHP_URL_HOST),
                'status'  => 'active',
                'api_key' => Str::random(32),
            ]);

            // Create Initial Admin User for Tenant
            $user = User::create([
                'name'      => $validated['admin_name'],
                'email'     => $validated['admin_email'],
                'password'  => Hash::make($validated['admin_password']),
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

            return redirect()->route('admin.tenants.index')
                ->with('status', 'success')
                ->with('message', 'Tenant and initial admin user created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('status', 'error')
                ->with('message', 'Failed to create tenant: ' . $e->getMessage());
        }
    }

    public function show(Tenant $tenant)
    {
        $tenant->loadCount(['users', 'orders']);

        return view('admin.tenants.show', compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $tenant->update($request->only('name', 'domain', 'status'));

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant updated successfully.');
    }

    public function toggleStatus(Tenant $tenant)
    {
        $newStatus = $tenant->status === 'active' ? 'inactive' : 'active';
        $tenant->update(['status' => $newStatus]);

        $label = ucfirst($newStatus);
        return back()->with('success', "Tenant marked as {$label}.");
    }

    public function makeDefault(Tenant $tenant)
    {
        // Remove default flag from all others
        Tenant::where('is_default', true)->update(['is_default' => false]);

        $tenant->update(['is_default' => true]);

        return back()->with('success', "'{$tenant->name}' is now the default tenant.");
    }
}
