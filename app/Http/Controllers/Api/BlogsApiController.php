<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Blog;
use App\Models\BlogCategory;

class BlogsApiController extends Controller{
    public function index(){
        $blogCategory = BlogCategory::where('status','active')->get();
        $blogs = Blog::where('status','active')->get();
        
        return response()->json([

        'blogs'=> $blogs,
        'blog_category' =>$blogCategory,


        ]);
    }
}