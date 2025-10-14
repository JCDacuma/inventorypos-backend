<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $category = ProductCategory::all();
        return response()->json($category);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoryName'=>'required|string|unique:product_categories,category_name',
            'categoryDescription'=> 'sometimes|string|max:255'
        ]);

        ProductCategory::create([
            'category_name' => $validated['categoryName'],
            'description' => $validated['categoryDescription']
        ]);

        return response()->json(['message'=>'Category has been successfully registered'],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, String $id)
{
    $productCategory = ProductCategory::findOrFail($id);

    $validated = $request->validate([
        'categoryName' => 'sometimes|string|unique:product_categories,category_name,' . $productCategory->id,
        'categoryDescription' => 'sometimes|string|max:255'
    ]);

    $productCategory->update([
        'category_name' => $validated['categoryName'],
        'description' => $validated['categoryDescription'] ?? $productCategory->category_description,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Category has been successfully updated.',
    ], 200);
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCategory $productCategory)
    {
        //
    }
}
