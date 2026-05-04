<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProviderService;
use App\Models\Service;
use App\Models\Country;
use App\Models\Provider;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    /**
     * Display a listing of provider services and their costs.
     */
    public function index()
    {
        $services = ProviderService::with(['provider', 'service', 'country'])
            ->paginate(20);
            
        $globalMarkup = SystemSetting::get('global_markup', 0.50);
        
        return view('admin.pricing.index', compact('services', 'globalMarkup'));
    }

    /**
     * Show the form for creating a new pricing rule (ProviderService).
     */
    public function create()
    {
        $providers = Provider::all();
        $services = Service::all();
        $countries = Country::all();
        
        return view('admin.pricing.create', compact('providers', 'services', 'countries'));
    }

    /**
     * Store a new ProviderService record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:providers,id',
            'service_id' => 'required|exists:services,id',
            'country_id' => 'required|exists:countries,id',
            'base_cost' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
        ]);

        ProviderService::create($validated);

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Pricing rule created successfully.');
    }

    /**
     * Edit a ProviderService record.
     */
    public function edit(ProviderService $pricing)
    {
        $providers = Provider::all();
        $services = Service::all();
        $countries = Country::all();
        
        return view('admin.pricing.edit', compact('pricing', 'providers', 'services', 'countries'));
    }

    /**
     * Update a ProviderService record.
     */
    public function update(Request $request, ProviderService $pricing)
    {
        $validated = $request->validate([
            'base_cost' => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
        ]);

        $pricing->update($validated);

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Pricing rule updated successfully.');
    }

    /**
     * Remove a ProviderService record.
     */
    public function destroy(ProviderService $pricing)
    {
        $pricing->delete();

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Pricing rule deleted successfully.');
    }

    /**
     * Toggle the availability status.
     */
    public function toggleStatus(ProviderService $pricing)
    {
        $pricing->update(['is_available' => !$pricing->is_available]);

        return back()->with('success', 'Pricing status toggled.');
    }

    /**
     * Bulk update markup or base costs.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'global_markup' => 'nullable|numeric|min:0',
            'adjustment_type' => 'required|in:fixed,percent',
            'adjustment_value' => 'nullable|numeric',
        ]);

        if ($request->filled('global_markup')) {
            SystemSetting::set('global_markup', $request->global_markup, 'pricing', 'float');
        }

        if ($request->filled('adjustment_value')) {
            $value = $request->adjustment_value;
            if ($request->adjustment_type === 'fixed') {
                ProviderService::query()->increment('base_cost', $value);
            } else {
                // Percent adjustment: cost * (1 + value/100)
                // This is a bit tricky with increment(), so we do a raw query or loop
                ProviderService::all()->each(function($ps) use ($value) {
                    $ps->base_cost *= (1 + $value / 100);
                    $ps->save();
                });
            }
        }

        return back()->with('success', 'Pricing rules updated in bulk.');
    }
}
