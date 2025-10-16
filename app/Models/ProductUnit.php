<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    protected $fillable = [
        'unitname',
        'symbol',
        'description',
        'unit_status'
    ];

    public function product(){
        return $this ->hasMany(Product::class, 'unit_id');
    }
}
