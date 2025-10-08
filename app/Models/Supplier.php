<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'suppliername',
        'supplier_address',
        'shipping_fee',
        'vat_registered',
        'supplier_contact_id',
        'status',
    ];

    protected $casts = [
        'vat_registered' => 'boolean',
    ];

    public function contact(){
        return $this -> belongsTo(SupplierContact::class, 'supplier_contact_id');
    }
}
