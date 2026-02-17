<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Display featured products for homepage
     */
    public function index()
    {
        $products = Product::where('is_featured', true)
            ->with('category')
            ->get();

        return Inertia::render('Products/Index', [
            'products' => $products
        ]);
    }

    /**
     * Display single product by slug
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with('category')
            ->first();

        if (!$product) {
            abort(404);
        }

        return Inertia::render('ProductDetail', [
            'product' => $product
        ]);
    }

    /**
     * Display products by category slug
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            abort(404);
        }

        $products = Product::where('category_id', $category->id)
            ->with('category')
            ->get();

        return Inertia::render('CategoryPage', [
            'category' => $category,
            'products' => $products
        ]);
    }

    /**
     * Search products by query parameter
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        $products = Product::where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('name_bn', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhere('description_bn', 'like', "%{$query}%");
        })
        ->with('category')
        ->get();

        return Inertia::render('SearchResults', [
            'products' => $products,
            'query' => $query
        ]);
    }
}
