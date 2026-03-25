<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        $categories = ProductCategory::all(); // All categories for dropdown

        return view('admin.pages.products', compact('products', 'categories'));
    }

    /**
     * Store a newly created product.
     */
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

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'size' => 'nullable|string|max:100',
            'brand' => 'nullable|string|max:100',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:0,1',
            'featured' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePaths = $product->images ?? [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }

        if ($request->has('remove_images')) {
            foreach ($request->remove_images as $index) {
                if (isset($imagePaths[$index])) {
                    Storage::disk('public')->delete($imagePaths[$index]);
                    unset($imagePaths[$index]);
                }
            }
            $imagePaths = array_values($imagePaths);
        }

        $validated['images'] = $imagePaths;
        $validated['in_stock'] = $validated['stock'] > 0;
        $validated['featured'] = $request->has('featured');
        $validated['status'] = $request->status;

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
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
}
