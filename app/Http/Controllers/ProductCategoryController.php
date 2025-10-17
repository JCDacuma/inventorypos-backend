<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $category = ProductCategory::where('category_status', '!=', 'Deleted')->get();
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
            'categoryName'=>['required','string',  Rule::unique('product_categories','category_name')->where(function($query){
            $query->where('category_status', '!=', 'Deleted');
        }),],
            'categoryDescription'=> 'sometimes|string|max:255',
            'category_status'=>'sometimes|string|in:Active,Deleted'
        ]);

        ProductCategory::create([
            'category_name' => $validated['categoryName'],
            'description' => $validated['categoryDescription'] ?? '',
            'category_status'=>$validated['category_status'] ?? 'Active',
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
        'categoryName' => [
            'sometimes',
            'string',
            Rule::unique('product_categories', 'category_name')
                ->ignore($id)
                ->where(function ($query) {
                    $query->where('category_status', '!=', 'Deleted');
                }),
        ],
        'categoryDescription' => 'sometimes|string|max:255',
        'category_status' => 'sometimes|string|in:Active,Deleted',
            ]);

            $productCategory->update([
                'category_name' => $validated['categoryName'] ?? $productCategory->category_name,
                'category_description' => $validated['categoryDescription'] ?? $productCategory->category_description,
                'category_status' => $validated['category_status'] ?? 'Active',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Category has been successfully updated.',
            ], 200);
        }


        

    public function softdelete(Request $request, String $id)
        {
            $productcategory = ProductCategory::findOrFail($id);

            $validated = $request->validate([
                'category_status' => 'sometimes|string|in:Deleted',
            ]);

            $categoryname = $productcategory->category_name;

            $productcategory->update([
                'category_status' => $validated['category_status'] ?? 'Deleted',
            ]);

            return response()->json([
                'message' => "Successfully Deleted Category $categoryname"
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
