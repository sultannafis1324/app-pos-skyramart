<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Get per_page value from request, default to 10
        $perPage = $request->input('per_page', 10);
        
        // Validate per_page to only allow specific values
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }
        
        // Get categories with pagination
        $categories = Category::withCount('products')
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);
        
        // If it's an AJAX request, return JSON
        if ($request->expectsJson()) {
            return response()->json($categories);
        }
        
        // Otherwise return the view
        return view('categories.index', compact('categories'));
    }

    public function apiIndex()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'is_active' => 'boolean'
        ]);

        // Set default value for is_active if not provided
        $validated['is_active'] = $request->has('is_active') ? (bool)$request->is_active : true;

        $category = Category::create($validated);
        
        // If it's an AJAX request, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Category created successfully',
                'data' => $category
            ], 201);
        }
        
        // Otherwise redirect with success message
        return redirect()->route('categories.index')
                        ->with('success', 'Category created successfully!');
    }

    public function show(Category $category)
    {
        // Load the category with its products
        $category->load('products');
        
        // If it's an AJAX request, return JSON
        if (request()->expectsJson()) {
            return response()->json($category);
        }
        
        // Otherwise return the view
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        // Load products count for the category
        $category->loadCount('products');
        
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'is_active' => 'boolean'
        ]);

        // Handle checkbox value
        $validated['is_active'] = $request->has('is_active') ? (bool)$request->is_active : false;

        $category->update($validated);
        
        // If it's an AJAX request, return JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Category updated successfully',
                'data' => $category
            ]);
        }
        
        // Otherwise redirect with success message
        return redirect()->route('categories.index', $category)
                        ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Cannot delete category with associated products'
                ], 422);
            }
            
            return redirect()->route('categories.index')
                            ->with('error', 'Cannot delete category with associated products');
        }

        $categoryName = $category->name;
        $category->delete();
        
        // If it's an AJAX request, return JSON
        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Category deleted successfully'
            ]);
        }
        
        // Otherwise redirect with success message
        return redirect()->route('categories.index')
                        ->with('success', "Category '{$categoryName}' deleted successfully!");
    }
}