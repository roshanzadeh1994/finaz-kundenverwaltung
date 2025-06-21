<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API-Endpunkte für Kundenverwaltung

Route::post('/customers', [CustomerController::class, 'store']);  // Neuen Kunden erstellen
Route::get('/customers', [CustomerController::class, 'index']);  // Alle Kunden abrufen
Route::get('/customers/{id}', [CustomerController::class, 'show']); // Einzelnen Kunden abrufen
Route::put('/customers/{id}', [CustomerController::class, 'update']); // Bestehenden Kunden aktualisieren
Route::delete('/customers/{id}', [CustomerController::class, 'destroy']); // Kunden löschen
