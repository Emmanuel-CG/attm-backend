<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // 🔹 GUARDAR CONTACTO
    public function store(Request $request)
    {
        $request->validate([
            'car_id'      => 'required|integer',
            'buyerName'   => 'required|string',
            'buyerEmail'  => 'required|email',
            'buyerPhone'  => 'required|string',
            'message'     => 'required|string',
        ]);

        $contact = Contact::create([
            'car_id'      => $request->car_id,
            'buyerName'   => $request->buyerName,
            'buyerEmail'  => $request->buyerEmail,
            'buyerPhone'  => $request->buyerPhone,
            'message'     => $request->message,
            'offeredPrice'=> $request->offeredPrice,
            'status'      => 'new',
        ]);

        return response()->json([
            'message' => 'Contacto creado',
            'contact' => $contact
        ], 201);
    }

    // 🔹 LISTAR TODOS
    public function index()
    {
        return Contact::latest()->get();
    }

    // 🔹 MARCAR COMO LEÍDO
    public function markAsRead($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        $contact->status = 'read';
        $contact->save();

        return response()->json([
            'message' => 'Actualizado',
            'contact' => $contact
        ], 200);
    }

    public function byCar($carId)
{
    return Contact::where('car_id', $carId)
        ->latest()
        ->get();
}
}