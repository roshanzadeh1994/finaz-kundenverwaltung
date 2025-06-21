<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{

    // Speichert einen neuen Kunden in der Datenbank
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

    // Gibt eine Liste aller Kunden zurück
    public function index()
    {
        $customers = \App\Models\Customer::all();

        return response()->json([
            'status' => 'ok',
            'customers' => $customers
        ]);
    }

    // Gibt einen bestimmten Kunden anhand der ID zurück
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

    // Aktualisiert einen bestehenden Kunden
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
            'message' => 'Kunde erfolgreich aktualisiert ✅',
            'customer' => $customer
        ]);
    }

    // Löscht einen Kunden anhand der ID
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
