<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::whereNotIn('product_status',['Archieved', 'Deleted'])->get()->map(function($item){
            $item->product_image_url = asset('storage/'.$item->product_image);
            return $item;
        });
        return response()->json($product);
    }

   public function getEditProduct($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->product_image_url = $product->product_image 
            ? asset('storage/'.$product->product_image) 
            : asset('');

        return response()->json([
            'success' => true,
            'product' => $product
        ], 200);
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
                    'productimage' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
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
                    'description' => $validated['description'] ?? "",
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Product successfully registered',
                    'product' => $product
                ], 201);

            } catch (ValidationException $e) {
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
        {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'productname' => 'sometimes|string|max:150|unique:products,productname,' . $id,
                'category' => 'sometimes|integer|exists:product_categories,id',
                'unit' => 'sometimes|integer|exists:product_units,id',
                'markupprice' => 'sometimes|numeric|min:0',
                'rawprice' => 'sometimes|numeric|min:0',
                'sellingprice' => 'sometimes|numeric|min:0',
                'istaxable' => 'sometimes|boolean',
                'status' => 'sometimes|string',
                'reorderlevel' => 'sometimes|integer|min:1',
                'description' => 'nullable|string|max:255',
                'productimage' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            if ($request->hasFile('productimage')) {

            if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                Storage::disk('public')->delete($product->product_image);
            }

            $file = $request->file('productimage');

            $filename = time() . '_' . str_replace(' ', '_', strtolower($request->productname)) . '.' . $file->getClientOriginalExtension();
            $imagePath = $file->storeAs('productimage', $filename, 'public');
            $product->product_image = $imagePath;
        }


            $updateData = [
            'productname'     => $validated['productname'] ?? $product->productname,
            'category_id'     => $validated['category'] ?? $product->category_id,
            'unit_id'         => $validated['unit'] ?? $product->unit_id,
            'markup_price'    => $validated['markupprice'] ?? $product->markup_price,
            'raw_price'       => $validated['rawprice'] ?? $product->raw_price,
            'selling_price'   => $validated['sellingprice'] ?? $product->selling_price,
            'taxable'         => $validated['istaxable'] ?? $product->taxable,
            'product_status'  => $validated['status'] ?? $product->product_status,
            'reorder_level'   => $validated['reorderlevel'] ?? $product->reorder_level,
            'description'     => $validated['description'] ?? "",
            'product_image'   => $validated['product_image'] ?? $product->product_image,
        ];

            $product->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Product successfully updated',
                'data' => $product->load(['category', 'unit']),
            ]);
        }


        public function batchupdate(Request $request){
            $validated = $request->validate([
                'request'=>'required|array',
                'request.*.id'=>'required|integer|exists:products,id',
                'request.*.category_id' => 'sometimes|integer|exists:product_categories,id',
                'request.*.unit_id' => 'sometimes|integer|exists:product_units,id',
                'request.*.product_status' => 'sometimes|string',
                'request.*.taxable' => 'sometimes|boolean',
            ]);

                foreach($validated['request'] as $productData){
                    $product = Product::find($productData['id']);

                    if($product){
                        $product->fill(array_filter(
                            [
                                'category_id' => $productData['category_id'] ?? null,
                                'unit_id'=> $productData['unit_id'] ?? null,
                                'taxable'=> $productData['taxable'] ?? null,
                                'product_status'=> $productData['product_status'] ?? null,
                            ],fn($p) => !is_null($p)
                        ));

                        $product->save();
                    }
                }

                return response()->json(['message' => 'successfully updated all the selected Product'], 200);
        }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }

    
}


