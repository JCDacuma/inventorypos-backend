<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class ProductSupplierController extends Controller
{

    /* --------------- Assign product to supplier  --------------- */

    public function AssignProductToSupplier(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'product_id' => 'required|exists:products,id'
        ]);

        $supplier = Supplier::findOrFail($validated['supplier_id']);
        $productId = $validated['product_id'];

        //check if exists
        $exists = $supplier->products()->where('product_id',$productId)->first();

        //assigning
        if($exists){
            $supplier->products()->updateExistingPivot($productId, ['status' => 'Active']);
        }else{
            $supplier->products()->attach($productId,['status' => 'Active']);
        }

        return response()->json(['message' => 'successfully assigned product']);
    }

    /* ========================= bulk supplier assignment to the selected product ============================ */
        public function bulkSupplierAssign(Request $request){
        $validated = $request->validate([
            'request'=>'required|array',
            'request.*.supplier_id' => 'required|exists:suppliers,id',
            'request.*.product_id'  => 'required|exists:products,id',
        ]);

        $supplierId = $validated['request'][0]['supplier_id'];
        $supplier = Supplier::findOrFail($supplierId );

        foreach($validated['request'] as $requestData){
            $productId = $requestData['product_id'];

            $supplier->products()->syncWithoutDetaching([$productId => ['status' => 'Active']]);
        }
        return response()->json(['message' => 'successfully registered supplier']);
    }


    /* ==================== Unnasign product to supplier  ======================= */

        public function unassignProductToSupplier(Request $request)
        {
            $validated = $request->validate([
                'supplier_id' => 'required|exists:suppliers,id',
                'product_id'  => 'required|exists:products,id',
            ]);

            $supplier = Supplier::findOrFail($validated['supplier_id']);
            $productId = $validated['product_id'];


            $exists = $supplier->products()
                ->where('product_id', $productId)
                ->first();

            if ($exists) {
                
                $supplier->products()->updateExistingPivot($productId, [
                    'status' => 'Removed',
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Successfully unassigned product.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Unable to unassign — product not assigned to the selected supplier.',
            ], 404);
        }

     
    /* ================= Get suppliers with Active status in Selected Product  ================= */

            public function getSupplierFromProduct($productId)
            {
                $product = Product::with('suppliers')->findOrFail($productId);

                $suppliers = $product->suppliers->map(function ($supplier) {
                    return [
                        'id' => $supplier->id,
                        'suppliername' => $supplier->suppliername,
                        'shipping_fee' => $supplier->shipping_fee,
                        'supplier_address' => $supplier->supplier_address,
                        'supplier_contact' => $supplier->name_contact,
                        'supplier_status' => $supplier->pivot->status,
                        'supplierVatStatus' => $supplier->vat_registered,
                    ];
                });

                return response()->json([
                    'product_id' => $product->id,
                    'productname' => $product->productname,
                    'suppliers' => $suppliers,
                ]);
            }


        /* ================= Get unnasigne supplier for the Selected product ================ */
            public function getUnassignedSuppliers($productId)
            {
                $product = Product::findOrFail($productId);

                $suppliers = Supplier::whereDoesntHave('products', function($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->orWhereHas('products', function($query) use ($product) {
                    $query->where('product_id', $product->id)
                        ->where('product_suppliers.status', 'Removed');
                })
                ->with('contact')
                ->get();

                return response()->json(['supplier' => $suppliers]);
            }


        /* ================= Get assigned supplier for bulk Selected product ================ */
            public function supplierFromBulkProduct(Request $request){
                $validated = $request->validate([
                    'product_ids' => 'required|array',
                    'product_ids.*' => 'exists:products,id'
                ]);

                $productIds = $validated['product_ids'];

                $suppliers = Supplier::whereHas('products', function ($query) use ($productIds){
                    $query->whereIn('product_id',$productIds)->where('product_suppliers.status','Active');
                })->with(['contact'])->get();

                return response()->json(["supplier" => $suppliers]);
            }



}
