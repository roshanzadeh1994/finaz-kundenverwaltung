<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{


public function store(Request $request)
{

    $validated = $request->validate([
        'nachname' => 'required|string|max:255',
        'vorname' => 'required|string|max:255',
        'email' => 'required|email|unique:customers',
        'telefon' => 'required|string|max:50',
        'strasse' => 'required|string|max:255',
        'plz' => 'required|string|max:20',
        'ort' => 'required|string|max:255',
    ]);


    $customer = Customer::create($validated);



    
    
    return response()->json([
        'status' => 'ok',
        'customer' => $customer,
    ], 201);
}


public function index()
{
    $customers = \App\Models\Customer::all();

    return response()->json([
        'status' => 'ok',
        'customers' => $customers
    ]);
}



public function show($id)
{
    $customer = \App\Models\Customer::find($id);

    if (!$customer) {
        return response()->json([
            'status' => 'error',
            'message' => 'Kunde nicht gefunden.'
        ], 404);
    }

    return response()->json([
        'status' => 'ok',
        'customer' => $customer
    ]);
}


public function update(Request $request, $id)
{
    $customer = \App\Models\Customer::find($id);

    if (!$customer) {
        return response()->json([
            'status' => 'error',
            'message' => 'Kunde nicht gefunden.'
        ], 404);
    }

    $validated = $request->validate([
        'nachname' => 'required|string|max:255',
        'vorname' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email,' . $id,
        'telefon' => 'required|string|max:50',
        'strasse' => 'required|string|max:255',
        'plz' => 'required|string|max:20',
        'ort' => 'required|string|max:255',
    ]);

    $customer->update($validated);

    return response()->json([
        'status' => 'ok',
        'customer' => $customer
    ]);
}



public function destroy($id)
{
    $customer = \App\Models\Customer::find($id);

    if (!$customer) {
        return response()->json([
            'status' => 'error',
            'message' => 'Kunde nicht gefunden.'
        ], 404);
    }

    $customer->delete();

    return response()->json([
        'status' => 'ok',
        'message' => 'Kunde wurde gelöscht.'
    ]);
}



}
