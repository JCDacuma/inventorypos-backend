<?php

namespace App\Http\Controllers;

use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unit = ProductUnit::all();
        return response()->json($unit);
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
    try {
     
        $validated = $request->validate([
            'unitname'   => 'required|string|unique:product_units,unitname',
            'symbol'     => 'required|string|unique:product_units,symbol',
            'description'=> 'sometimes|string|max:255',
            'unitstatus' => 'sometimes|string',
        ]);

        $unit = ProductUnit::create([
            'unitname'   => $validated['unitname'],
            'symbol'     => $validated['symbol'],
            'description'=> $validated['description'] ?? null,
            'unit_status'=> $validated['unitstatus'] ?? 'Active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully registered unit.',
            'unit'    => $unit,
        ], 201);

    } catch (ValidationException $e) {
       
        return response()->json([
            'success' => false,
            'message' => 'Validation failed. Please check your input.',
            'errors'  => $e->errors(), 
        ], 422);

    } catch (\Throwable $e) {
       
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred while saving the unit.',
            'error'   => $e->getMessage(), 
        ], 500);
    }
}


    /**
     * Display the specified resource.
     */
    public function show(ProductUnit $productUnit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductUnit $productUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
 public function update(Request $request, string $id)
{
    $unit = ProductUnit::findOrFail($id);

    try {
        $validated = $request->validate([
            'unitname'   => 'sometimes|string|unique:product_units,unitname,' . $unit->id,
            'symbol'     => 'sometimes|string|unique:product_units,symbol,' . $unit->id,
            'description'=> 'sometimes|string|max:255',
            'unitstatus' => 'sometimes|string',
        ]);

        $unit->update([
            'unitname'   => $validated['unitname'] ?? $unit->unitname,
            'symbol'     => $validated['symbol'] ?? $unit->symbol,
            'description'=> $validated['description'] ?? $unit->description,
            'unit_status'=> $validated['unitstatus'] ?? $unit->unit_status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully updated the unit.',
            'unit'    => $unit,
        ], 200);

    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed. Please check your input.',
            'errors'  => $e->errors(),
        ], 422);

    } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => 'An unexpected error occurred while updating the unit.',
            'error'   => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductUnit $productUnit)
    {
        //
    }
}
