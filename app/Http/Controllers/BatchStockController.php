<?php

namespace App\Http\Controllers;

use App\Models\ProductStock;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BatchStockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

//inventory 
   public function getInventoryDisplay()
{
    $products = Product::select(
            'id',
            'product_code',
            'productname',
            'selling_price',
            'category_id',
            'unit_id',
            'product_status',
            'reorder_level',
        )
        ->with([
            'category:id,category_name',
            'unit:id,symbol',
        ])
        ->withSum(['productStocks as total_quantity' => function ($query) {
            $query->where('status', 'Active');
        }], 'quantity')
        ->withMax(['productStocks as last_movement' => function ($query) {
            $query->where('status', 'Active');
        }], 'last_moved')
        ->whereNotIn('product_status', ['Deleted'])
        ->get();

         
        $products->transform(function ($item) {
            if ($item->last_movement) {
                $item->last_movement = Carbon::parse($item->last_movement)
                    ->format('Y-m-d h:i A'); 
            }
            return $item;
        });

    return response()->json(['inventory' => $products]);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductStock $ProductStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductStock $ProductStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductStock $ProductStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductStock $ProductStock)
    {
        //
    }
}
