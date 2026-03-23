<?php

namespace App\Http\Controllers;

use App\Models\Service; 
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('id', 'DESC')->paginate(10);
        return view('admin.pages.test-services', compact('services'));
    }

    public function store(Request $request)
    {
        // Validate top-level fields
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name',
            'icon' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
            // Validate parameters as array
            'parameters' => 'nullable|array',
            'parameters.duration' => 'required_with:parameters|string|max:100',
            'parameters.price' => 'required_with:parameters|numeric|min:0',
        ]);

        Service::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'status' => $request->status,
            'parameters' => $request->parameters, // Laravel auto-converts to JSON
        ]);

        return redirect()->route('services.index')->with('success', 'Service created successfully.');
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $service->id,
            'icon' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
            'parameters' => 'nullable|array',
            'parameters.duration' => 'required_with:parameters|string|max:100',
            'parameters.price' => 'required_with:parameters|numeric|min:0',
        ]);

        $service->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'status' => $request->status,
            'parameters' => $request->parameters,
        ]);

        return redirect()->route('services.index')->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}