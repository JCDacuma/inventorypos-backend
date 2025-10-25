<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Product extends Model
{
    protected $fillable = [
        'product_code',
        'product_image',
        'productname',
        'category_id',
        'unit_id',
        'markup_price',
        'raw_price',
        'selling_price',
        'taxable',
        'product_status',
        'reorder_level',
        'description',
    ];

    protected $casts = [
        'taxable' => 'boolean'
    ];

    protected $appends = ['product_category', 'unit_symbol' , ];
  
   //category
   public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    //unit
    public function unit(){
    return $this->belongsTo(ProductUnit::class, 'unit_id');
    }

    //supplier pivot
    public function suppliers(){
        return $this->belongsToMany(Supplier::class, 'product_suppliers')
        ->withPivot('status')
        ->wherePivot('status','Active')
        ->withTimestamps();
    }
   
    //all supplier for pivot
    public function allSuppliers(){
        return $this->belongsToMany(Supplier::class, 'product_suppliers')
        ->withPivot('status')
        ->withTimestamps();
    }

    public function productStocks(){
        return $this->hasMany(ProductStock::class, 'product_id');
    }

    //append
    //get product_category
    public function getProductCategoryAttribute(){
        return $this ->category ? $this->category->category_name : null;
    }

    //get product_unit
    public function getUnitSymbolAttribute(){
        return $this ->unit ? $this->unit->symbol : null;
    }

 

    
}
