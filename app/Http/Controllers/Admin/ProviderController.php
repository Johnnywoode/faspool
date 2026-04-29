<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::all();
        return view('admin.providers.index', compact('providers'));
    }

    public function create()
    {
        return view('admin.providers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:providers',
            'adapter' => 'required|string|max:255',
            'api_key' => 'required|string',
            'status' => 'required|in:active,inactive',
            'config' => 'nullable|json',
        ]);

        $config = ['api_key' => $validated['api_key']];
        
        // Merge additional config if provided
        if (!empty($validated['config'])) {
            $additionalConfig = json_decode($validated['config'], true);
            if (is_array($additionalConfig)) {
                $config = array_merge($config, $additionalConfig);
            }
        }

        Provider::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'adapter' => $validated['adapter'],
            'config' => $config,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.providers.index')
            ->with('success', 'Provider created successfully.');
    }

    public function edit(Provider $provider)
    {
        return view('admin.providers.edit', compact('provider'));
    }

    public function update(Request $request, Provider $provider)
    {
        $request->validate([
            'api_key' => 'required|string',
            'status' => 'required|in:active,inactive',
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
        // Don't allow deleting if there are orders
        if ($provider->orders()->count() > 0) {
            return back()->with('error', 'Cannot delete provider with existing orders.');
        }

        $provider->delete();

        return redirect()->route('admin.providers.index')
            ->with('success', 'Provider deleted successfully.');
    }
}
