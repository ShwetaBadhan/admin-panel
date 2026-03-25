<?php

namespace App\Http\Controllers;

use App\Models\ServicesSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicesSectionController extends Controller
{
    /**
     * GET /services
     * Show form + Auto-create record if not exists
     */
    public function index()
    {
        // Pehla record fetch karo
        $section = ServicesSection::first();
        
       
        if (!$section) {
            $section = ServicesSection::create([
                'subtitle' => 'OUR BEST SERVICES',
                'main_heading' => 'We Providing High Quality',
                'service_items' => [
                    ['title' => 'Organic Product', 'link' => '#'],
                    ['title' => 'Growth Providing', 'link' => '#'],
                    ['title' => 'Agriculture Staff', 'link' => '#'],
                    ['title' => 'Organic Farming', 'link' => '#'],
                ],
                'active_item_title' => 'Agriculture Staff',
                'active_item_description' => 'Veritatis eligendi, dignissimo ferm lieentum mus aute pulvinar platea anie massa rutr a ignissimo ferm lieentum.',
                'is_active' => true,
            ]);
        }

       
        return view('admin.pages.services-section', compact('section'));
    }

    /**
     * PUT /services/{section}
     * Update existing record (Route Model Binding auto-fetches $section)
     */
    public function update(Request $request, ServicesSection $section)
    {
        $request->validate([
            'subtitle' => 'required|string|max:255',
            'main_heading' => 'required|string|max:255',
            'service_items' => 'required|array|min:1',
            'service_items.*.title' => 'required|string|max:255',
            'service_items.*.link' => 'nullable|string|max:255',
            'active_item_title' => 'required|string|max:255',
            'active_item_description' => 'required|string',
            'read_more_link' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle Image Upload
        if ($request->hasFile('image')) {
            if ($section->image) Storage::disk('public')->delete($section->image);
            $section->image = $request->file('image')->store('services', 'public');
        }

        // Handle Icon Upload
        if ($request->hasFile('icon')) {
            if ($section->icon) Storage::disk('public')->delete($section->icon);
            $section->icon = $request->file('icon')->store('services/icons', 'public');
        }

        // Update all fields
        $section->update([
            'subtitle' => $request->subtitle,
            'main_heading' => $request->main_heading,
            'service_items' => $request->service_items, // Array directly saved as JSON
            'active_item_title' => $request->active_item_title,
            'active_item_description' => $request->active_item_description,
            'read_more_link' => $request->read_more_link,
        ]);

        return redirect()->back()->with('success', 'Services section updated successfully!');
    }
}