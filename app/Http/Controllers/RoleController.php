<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::where('status', '!=', 'Deleted')->get();
        return response()->json($roles);
    }
    /**
     * Check Rolename is exist
     */
    public function checkRole(Request $request){
        $role = $request->input('rolename');
        $exists = Role::where('role_name', $role)->where('status','!=','Deleted')->exists();

        return response()->json(['exists' => $exists]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'role_name' => 'required|string|max:255',
                'can_edit_price'=>'boolean',
                'can_edit_item_info'=>'boolean',
                'can_edit_stocks'=>'boolean',
                'can_order_supplies'=>'boolean',
                'can_delete' => 'boolean',
                'is_admin'=>'boolean',
                'status' => 'string',
            ]
        );

        $role = Role::create($validated);
        return response()->json($role, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);

        $validated = $request -> validate([
            'role_name' => 'sometimes|required|string|max:255',
            'can_edit_price'=>'sometimes|boolean',
            'can_edit_item_info'=>'sometimes|boolean',
            'can_edit_stocks'=>'sometimes|boolean',
            'can_order_supplies'=>'sometimes|boolean',
            'can_delete' => 'sometimes|boolean',
            'is_admin'=>'sometimes|boolean',
            'status' => 'sometimes|string',
        ]);

        $role->update( $validated );

        return response()->json([
            "message" => "role has been update",
            "role" =>  $role
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
