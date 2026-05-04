<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::withCount('orders')->get();
        return view('admin.providers.index', compact('providers'));
    }

    public function create()
    {
        return view('admin.providers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'slug'   => 'required|string|max:255|unique:providers',
            'adapter' => 'required|string|max:255',
            'api_key' => 'required|string',
            'status'  => 'required|in:active,inactive',
            'config'  => 'nullable|json',
        ]);

        $config = ['api_key' => $validated['api_key']];

        if (!empty($validated['config'])) {
            $additionalConfig = json_decode($validated['config'], true);
            if (is_array($additionalConfig)) {
                $config = array_merge($config, $additionalConfig);
            }
        }

        Provider::create([
            'name'    => $validated['name'],
            'slug'    => $validated['slug'],
            'adapter' => $validated['adapter'],
            'config'  => $config,
            'status'  => $validated['status'],
        ]);

        return redirect()->route('admin.providers.index')
            ->with('success', 'Provider created successfully.');
    }

    public function show(Provider $provider)
    {
        $provider->loadCount('orders');
        return view('admin.providers.show', compact('provider'));
    }

    public function edit(Provider $provider)
    {
        return view('admin.providers.edit', compact('provider'));
    }

    public function update(Request $request, Provider $provider)
    {
        $request->validate([
            'api_key' => 'required|string',
            'status'  => 'required|in:active,inactive',
        ]);

        $config = $provider->config ?? [];
        $config['api_key'] = $request->api_key;

        $provider->config = $config;
        $provider->status = $request->status;
        $provider->save();

        return back()->with('success', 'Provider configuration updated successfully.');
    }

    public function destroy(Provider $provider)
    {
        if ($provider->orders()->count() > 0) {
            return back()->with('error', 'Cannot delete provider with existing orders.');
        }

        $provider->delete();

        return redirect()->route('admin.providers.index')
            ->with('success', 'Provider deleted successfully.');
    }

    public function toggleStatus(Provider $provider)
    {
        $newStatus = $provider->status === 'active' ? 'inactive' : 'active';
        $provider->update(['status' => $newStatus]);

        return back()->with('success', "Provider marked as {$newStatus}.");
    }

    public function numbers(Provider $provider)
    {
        // Fetch numbers currently assigned through this provider
        $orders = $provider->orders()
            ->with(['user', 'service', 'country'])
            ->whereNotNull('number')
            ->where('status', 'waiting_sms')
            ->paginate(20);

        return view('admin.providers.numbers', compact('provider', 'orders'));
    }

    public function syncNumbers(Provider $provider)
    {
        // TODO: pull live number inventory from provider API
        return back()->with('success', 'Numbers synced from provider.');
    }

    public function updateRates(Request $request, Provider $provider)
    {
        $request->validate([
            'markup_percent' => 'required|numeric|min:0|max:1000',
        ]);

        $config = $provider->config ?? [];
        $config['markup_percent'] = $request->markup_percent;
        $provider->update(['config' => $config]);

        return back()->with('success', 'Provider rates updated.');
    }

    public function rates(Provider $provider)
    {
        return view('admin.providers.rates', compact('provider'));
    }

    public function storeRates(Request $request, Provider $provider)
    {
        $request->validate([
            'service'  => 'required|string|max:255',
            'country'  => 'required|string|max:255',
            'rate'     => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
        ]);

        // TODO: persist to a provider_rates table
        return back()->with('success', 'Rate added successfully.');
    }

    public function updateRate(Request $request, Provider $provider, $rate)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0',
        ]);

        // TODO: update specific rate record
        return back()->with('success', 'Rate updated.');
    }

    public function deleteRate(Provider $provider, $rate)
    {
        // TODO: delete specific rate record
        return back()->with('success', 'Rate deleted.');
    }

    public function bulkUploadRates(Request $request, Provider $provider)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        // TODO: parse and import rates from CSV
        return back()->with('success', 'Rates imported from file.');
    }
}
