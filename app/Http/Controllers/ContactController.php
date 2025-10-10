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
            'email'=>'required|email|unique:supplier_contacts,email',
        ]);

        $contact = SupplierContact::create([
            'firstname'=>$validated['firstname'],
            'lastname'=>$validated['lastname'],
            'phonenumber'=>$validated['phonenumber'],
            'email'=>$validated['email'],
        ]);

        return response()->json(['message' => 'successfully registered new user' , 'contact' => $contact ], 201);
    }

    
    public function update(Request $request, SupplierContact $contact){
       

        $validated = $request->validate([
            'firstname'=>'sometimes|string|max:255',
            'lastname'=>'sometimes|string|max:255',
            'phonenumber'=> ['sometimes', 'regex:/^(?:\+?63|0)9\d{9}$/'],
            'email'=>'sometimes|email|unique:supplier_contacts,email,'. $contact->id
        ]);

        $contact->update([
            'firstname'=>$validated['firstname'] ?? $contact->firstname,
            'lastname'=>$validated['lastname'] ?? $contact->lastname,
            'phonenumber'=>$validated['phonenumber'] ?? $contact->phonenumber,
            'email'=>$validated['email'] ?? $contact->email,
        ]);

        return response()->json([
            'message' => 'Contact Successfully Registered', 'Contact' => $contact
        ], 200);
    }
}
