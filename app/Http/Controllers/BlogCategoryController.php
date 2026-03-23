<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {


        $categories = BlogCategory::latest()->get();
        return view('admin.pages.blog-categories', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name',
            'status' => 'required|in:active,inactive'
        ]);

        BlogCategory::create($request->only(['name', 'status']));

        return back()->with('success', 'Category added successfully!');
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:blog_categories,name,' . $blogCategory->id,
            'status' => 'required|in:active,inactive'
        ]);

        $blogCategory->update($request->only(['name', 'status']));

        return back()->with('success', 'Category updated successfully!');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();
        return back()->with('success', 'Category deleted successfully!');
    }
}