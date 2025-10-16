<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::whereNotIn('product_status',['Archieved', 'Deleted'])->get();
        return response()->json($product);
    }

    /**
     * Show the form for creating a new resource.
     */
   
    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    DB::beginTransaction();

    try {
        $validated = $request->validate([
            'productcode' => 'required|string|unique:products,product_code',
            'productimage' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'productname' => 'required|string|max:150|unique:products,productname',
            'category' => 'required|integer|exists:product_categories,id',
            'productunit' => 'required|integer|exists:product_units,id',
            'rawprice' => 'required|numeric|min:0',
            'markupprice' => 'required|numeric|min:0',
            'sellingprice' => 'required|numeric|min:0',
            'istaxable' => 'nullable|boolean',
            'status' => 'nullable|string|in:Active,Inactive',
            'reorderlevel' => 'required|integer|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        $imagename = $request->file('productimage')->store('productimage', 'public');

        $product = Product::create([
            'product_code' => $validated['productcode'],
            'product_image' => $imagename,
            'productname' => $validated['productname'],
            'category_id' => $validated['category'],
            'unit_id' => $validated['productunit'],
            'markup_price' => $validated['markupprice'],
            'raw_price' => $validated['rawprice'],
            'selling_price' => $validated['sellingprice'],
            'taxable' => $request->boolean('istaxable'),
            'product_status' => $validated['status'] ?? 'Active',
            'reorder_level' => $validated['reorderlevel'],
            'description' => $validated['description'],
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Product successfully registered',
            'product' => $product
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();

        return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => $e->errors(),
        ], 422);

    } catch (\Exception $e) {
        DB::rollBack();

        if (isset($imagename) && Storage::disk('public')->exists($imagename)) {
            Storage::disk('public')->delete($imagename);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to register product',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    
}


