<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $fillable = [
        'product_id', 
        'stock_code',
        'is_primary',
        'location',
        'quantity', 
        'status', 
        'last_moved', 
    ];

    protected $casts = [
      'is_primary' => 'boolean',
    ];

    public function products(){
      return  $this->belongsTo(Product::class,'product_id');
    }
}
