<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_image',
        'productname',
        'category_id',
        'markup_price',
        'raw_price',
        'selling_price',
        'taxable',
        'product_status',
        'unit',
        'reorder_level',
        'description',
    ];

    protected $casts = [
        'taxable' => 'boolean'
    ];

    protected $appends = ['product_category'];

   public function category()
{
    return $this->belongsTo(ProductCategory::class, 'category_id');
}

    //append
    public function getProductCategoryAttribute(){
        return $this ->category ? $this->category->category_name : null;
    }
}
