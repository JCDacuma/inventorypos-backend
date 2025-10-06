<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'role_name',
        'can_edit_price',
        'can_edit_item_info',
        'can_edit_stocks',
        'can_order_supplies',
        'can_delete',
        'is_admin',
        'status'
    ];

    protected $casts = [
        'can_edit_price' => 'boolean',
        'can_edit_item_info' => 'boolean',
        'can_edit_stocks' => 'boolean',
        'can_order_supplies' => 'boolean',
        'can_delete'=> 'boolean',
        'is_admin' => 'boolean',
    ];

    public function users(){
      return  $this -> hasMany(User::class, 'role_id');
    }

}
