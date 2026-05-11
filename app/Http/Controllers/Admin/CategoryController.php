<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('events')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.index', ['categories' => Category::withCount('events')->get()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100|unique:categories',
            'color_hex' => 'nullable|string|max:7',
        ]);

        Category::create([
            'name'        => $request->name,
            'description' => $request->description,
            'color_hex'   => $request->color_hex ?? '#1A56A0',
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category added successfully!');
    }

    public function edit(Category $category)
    {
        $categories = Category::withCount('events')->get();
        return view('admin.categories.index', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name'        => $request->name,
            'description' => $request->description,
            'color_hex'   => $request->color_hex ?? $category->color_hex,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
