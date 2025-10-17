<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ProductUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unit = ProductUnit::where('unit_status', '!=', 'Deleted')->get();
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
            'unitname' => [
                'required',
                'string',
                Rule::unique('product_units', 'unitname')
                    ->where(function ($query) {
                        $query->where('unit_status', '!=', 'Deleted');
                    }),
            ],
            'symbol' => [
                'required',
                'string',
                Rule::unique('product_units', 'symbol')
                    ->where(function ($query) {
                        $query->where('unit_status', '!=', 'Deleted');
                    }),
            ],
            'description' => 'sometimes|nullable|string|max:255',
            'unitstatus'  => 'sometimes|string',
        ]);

        $unit = ProductUnit::create([
            'unitname'   => $validated['unitname'],
            'symbol'     => $validated['symbol'],
            'description'=> $validated['description'] ?? "",
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
                        'unitname' => [
                            'sometimes',
                            'string',
                            Rule::unique('product_units', 'unitname')
                                ->ignore($unit->id)
                                ->where(function ($query) {
                                    $query->where('unit_status', '!=', 'Deleted');
                                }),
                        ],
                        'symbol' => [
                            'sometimes',
                            'string',
                            Rule::unique('product_units', 'symbol')
                                ->ignore($unit->id)
                                ->where(function ($query) {
                                    $query->where('unit_status', '!=', 'Deleted');
                                }),
                        ],
                            'description' => 'sometimes|nullable|string|max:255',
                            'unitstatus' => 'sometimes|string',
                        ]);

                        $unit->update([
                            'unitname'   => $validated['unitname'] ?? $unit->unitname,
                            'symbol'     => $validated['symbol'] ?? $unit->symbol,
                            'description'=> $validated['description'] ?? '',
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
                public function softdelete(Request $request, String $id)
                    {
                        $unit = ProductUnit::findOrFail($id);
                        
                        $validated = $request->validate([
                            'unit_status' => 'nullable|string|in:Deleted'
                        ]);

                        $unitname = $unit->unitname;

                        $unit->update([
                            'unit_status' => $validated['unit_status'] ?? 'Deleted',
                        ]);

                        return response()->json([
                            'message' => "Successfully deleted $unitname"
                        ], 200);
                    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductUnit $productUnit)
    {
        //
    }
}
