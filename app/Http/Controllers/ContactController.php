<?php

namespace App\Http\Controllers;
use App\Models\SupplierContact;
use Illuminate\Http\Request;


class ContactController extends Controller
{
    public function index(){
        $contact = SupplierContact::all();
        return response()->json($contact);
    }

    //store new contact supplier
    public function store(Request $request){
        $validated = $request->validate([
            'firstname'=>'required|string|max:255',
            'lastname'=>'required|string|max:255',
            'phonenumber'=> ['required', 'regex:/^(?:\+?63|0)9\d{9}$/'],
            'email'=>'required|string|unique:supplier_contacts,email',
        ]);

        $user = SupplierContact::create([
            'firstname'=>$validated['firstname'],
            'lastname'=>$validated['lastname'],
            'phonenumber'=>$validated['phonenumber'],
            'email'=>$validated['email'],
        ]);

        return response()->json(['message' => 'successfully registered new user' , 'user' => $user ], 201);
    }
}
