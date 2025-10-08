<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierContact extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'phonenumber',
        'email',
    ];

    public function suppliers(){
        return $this -> hasMany(Supplier::class, 'supplier_contact_id');
    }
}
