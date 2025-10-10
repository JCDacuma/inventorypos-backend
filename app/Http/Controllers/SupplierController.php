<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplier = Supplier::all();
        return response()->json( $supplier);
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
        $validate = $request->validate([
            'suppliername'=>'sometimes|string|max:255',
            'supplier_address'=>'sometimes|string|max:255',
            'shipping_fee'=>'sometimes|numeric',
            'vat_registered'=>'sometimes|boolean',
            'supplier_contact_id' => 'sometimes|integer|exists:supplier_contacts,id',
            'status'=>'sometimes|string|max:255'
        ]);

        $supplier = Supplier::create([
            'suppliername'=>$validate['suppliername'],
            'supplier_address'=>$validate['supplier_address'],
            'shipping_fee'=>$validate['shipping_fee'],
            'vat_registered'=>$validate['vat_registered'],
            'supplier_contact_id'=>$validate['supplier_contact_id'] ?? null,
            'status'=>$validate['status'],
        ]);

        return response()->json(['message' => 'successfully registered supplier', 'supplier' => $supplier], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'suppliername'=>'sometimes|string|max:255',
            'supplier_address'=>'sometimes|string|max:255',
            'shipping_fee'=>'sometimes|numeric',
            'vat_registered'=>'sometimes|boolean',
            'supplier_contact_id' => 'sometimes|integer|exists:supplier_contacts,id',
            'status'=>'sometimes|string|max:255'
        ]);

        $supplier->update([
            'suppliername'=>$validated['suppliername'] ?? $supplier->suppliername,
            'supplier_address'=>$validated['supplier_address'] ?? $supplier->supplier_address,
            'shipping_fee'=>$validated['shipping_fee'] ?? $supplier->shipping_fee,
            'vat_registered'=>$validated['vat_registered'] ?? $supplier->vat_registered,
            'supplier_contact_id'=>$validated['supplier_contact_id'] ?? $supplier->supplier_contact_id,
            'status'=>$validated['status'] ?? $supplier->status,
        ]);

        return response()->json(['message' => 'Supplier Successfully updated', 'supplier' => $supplier ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        //
    }

    //check availability suppliername
    public function checkSuppliername(Request $request){
        $supplierInput = $request->input('suppliername');
        $exists = Supplier::where('suppliername', $supplierInput)->exists();

        return response()->json(['exists'=> $exists]);
    }
}
