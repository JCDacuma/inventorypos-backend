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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
      public function store(Request $request)
    {
        try{
             $validated = $request->validate([
            'productimage' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'productname' => 'required|string|max:150|unique:products,productname',
            'category' => 'required|integer|exists:product_categories,id',
            'rawprice' => 'required|numeric|min:0',
            'markupprice' => 'required|numeric|min:0',
            'sellingprice' => 'required|numeric|min:0',
            'istaxable' => 'sometimes|boolean',
            'status' => 'sometimes|string',
            'unit' => 'required|string|max:50',
            'reorderlevel' => 'required|integer|min:0',
            'description' => 'required|string|max:255',
        ]);

        $imagename = null;

        if($request->hasFile('productimage')){
            $image = $request->file('productimage');
            $imagename = time() . '_' . str_replace(' ', '_', $image->getClientOriginalName());
            $image->storeAs('productimage',$imagename, 'public');
        }

        DB::beginTransaction();

            $product = Product::create([
            'product_image'=> $imagename,
            'productname'=> $validated['productname'],
            'category_id'=> $validated['category'],
            'markup_price'=> $validated['markupprice'],
            'raw_price'=> $validated['rawprice'],
            'selling_price'=> $validated['sellingprice'],
            'taxable'=> $validated['istaxable'] ?? false,
            'product_status'=> $validated['status'] ?? 'Active',
            'unit'=> $validated['unit'],
            'reorder_level'=> $validated['reorderlevel'],
            'description'=> $validated['description'],
            ]);

            DB::commit();

            return response()->json(['success' => true , 'message' => 'successfully registered product', 'product'=>$product], 201);
        }
        catch(ValidationException $e){
            return response()->json(['success' => false , 'message' => 'Invalid Input, There is error in your input'], 422);
        }
        catch(\Exception $e){
            DB::rollBack();

            if($imagename && Storage::disk('public')->exists('productimage/' . $imagename)){
                Storage::disk('public')->delete('productimage/' . $imagename);
            }

            return response()->json(['success' => false, 'message'=> 'failed to register product', 'error'=> $e->getMessage()],500);
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
