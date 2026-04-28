<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CcPointSetting;
class ProductController extends Controller
{
   public function index()
{
    $products = Product::with('category')->latest()->paginate(10);
    $categories = ProductCategory::all();
    $ccSetting = CcPointSetting::getCurrent(); // ✅ Load CC setting

    return view('admin.pages.products', compact('products', 'categories', 'ccSetting'));
}

public function store(Request $request)
{
    $validated = $request->validate([
        'category_id' => 'required|exists:product_categories,id',
        'name' => 'required|string|max:255',
        'sku' => 'required|string|unique:products,sku',
        'short_description' => 'nullable|string|max:500',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0|lt:price',
        'cc_points' => 'nullable|numeric|min:0', // ✅ Added
        'size' => 'nullable|string|max:100',
        'brand' => 'nullable|string|max:100',
        'stock' => 'required|integer|min:0',
        'status' => 'required|in:0,1',
        'featured' => 'boolean',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $imagePaths = [];
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('products', 'public');
            $imagePaths[] = $path;
        }
    }

    $validated['images'] = $imagePaths;
    $validated['in_stock'] = $validated['stock'] > 0;
    $validated['featured'] = $request->has('featured');
    $validated['status'] = $request->status;
    
    // ✅ Auto-calculate CC points if not provided
    if (empty($validated['cc_points'])) {
        $price = $validated['discount_price'] ?? $validated['price'];
        $validated['cc_points'] = CcPointSetting::calculateCCFromPrice($price);
    }

    Product::create($validated);

    return redirect()->route('products.index')
        ->with('success', 'Product created successfully!');
}

public function update(Request $request, Product $product)
{
    // Check if only CC points is being updated
    if ($request->only(['cc_points']) && !$request->has(['name', 'sku', 'price'])) {
        // CC-only update
        $validated = $request->validate([
            'cc_points' => 'required|integer|min:0',
        ]);
        
        $product->update($validated);
        
        return redirect()->back()
            ->with('success', 'CC Points updated successfully!');
    }
    
    // Full product update
    $validated = $request->validate([
        'category_id' => 'required|exists:product_categories,id',
        'name' => 'required|string|max:255',
        'sku' => 'required|string|unique:products,sku,' . $product->id,
        'price' => 'required|numeric|min:0',
        'discount_price' => 'nullable|numeric|min:0|lt:price',
        'cc_points' => 'required|integer|min:0',
        'size' => 'nullable|string|max:100',
        'brand' => 'nullable|string|max:100',
        'stock' => 'required|integer|min:0',
        'status' => 'required|in:0,1',
        'featured' => 'boolean',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    // ... rest of update logic
}

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Remove specific image from product.
     */
    public function removeImage(Request $request, Product $product)
    {
        $index = $request->index;
        $images = $product->images ?? [];

        if (isset($images[$index])) {
            Storage::disk('public')->delete($images[$index]);
            unset($images[$index]);
            $product->images = array_values($images);
            $product->save();
        }

        return response()->json(['success' => true]);
    }
    public function updateCC(Request $request, Product $product)
{
    $validated = $request->validate([
        'cc_points' => 'required|integer|min:0',
    ]);

    $product->update([
        'cc_points' => $validated['cc_points'],
    ]);

    return redirect()->back()
        ->with('success', 'CC Points updated successfully!');
}
}
