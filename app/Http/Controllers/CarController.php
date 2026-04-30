<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function store(Request $request)
    {
        $token = $request->header('Authorization');
        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        $request->validate([
            'brand'        => 'required|string',
            'model'        => 'required|string',
            'year'         => 'required|integer',
            'price'        => 'required|integer',
            'mileage'      => 'required|integer',
            'transmission' => 'required|string',
            'fuelType'     => 'required|string',
            'color'        => 'required|string',
            'location'     => 'required|string',
            'phone'        => 'required|string',
            'description'  => 'required|string',
        ]);

        $car = Car::create([
            'user_id'      => $user->id,
            'brand'        => $request->brand,
            'model'        => $request->model,
            'year'         => $request->year,
            'price'        => $request->price,
            'mileage'      => $request->mileage,
            'transmission' => $request->transmission,
            'fuelType'     => $request->fuelType,
            'color'        => $request->color,
            'location'     => $request->location,
            'phone'        => $request->phone,
            'description'  => $request->description,
        ]);

        // sumar auto al user
        $user->totalCars++;
        $user->save();

        return response()->json([
            'message' => 'Auto publicado correctamente',
            'car' => $car
        ], 201);
    }

    public function myCars(Request $request)
    {
        $token = $request->header('Authorization');
        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        return Car::where('user_id', $user->id)->get();
    }

public function show($id)
{
    $car = Car::with('user')->find($id);

    if (!$car) {
        return response()->json(['error' => 'Auto no encontrado'], 404);
    }

    return response()->json([
        'id'          => $car->id,
        'brand'       => $car->brand,
        'model'       => $car->model,
        'year'        => $car->year,
        'price'       => $car->price,
        'mileage'     => $car->mileage,
        'transmission'=> $car->transmission,
        'fuelType'    => $car->fuelType,
        'color'       => $car->color,
        'location'    => $car->location,
        'phone'       => $car->phone,
        'description' => $car->description,
        'images'      => $car->images,

        // 🔥 lo único extra que ocupas
        'sellerName'  => $car->user->name,
        'sellerPhone' => $car->user->phone,
    ]);
}


    public function index()
{
    return Car::orderBy('id', 'desc')->get();
}

public function update(Request $request, $id)
{
    $token = $request->header('Authorization');
    $user = User::where('api_token', $token)->first();

    if (!$user) {
        return response()->json(['error' => 'No autorizado'], 401);
    }

    $car = Car::where('id', $id)->where('user_id', $user->id)->first();

    if (!$car) {
        return response()->json(['error' => 'No encontrado'], 404);
    }

    $car->update($request->all());

    return response()->json([
        'message' => 'Auto actualizado correctamente',
        'car' => $car
    ]);
}
public function destroy(Request $request, $id)
{
    $token = $request->header('Authorization');
    $user = User::where('api_token', $token)->first();

    if (!$user) {
        return response()->json(['error' => 'No autorizado'], 401);
    }

    // Solo puede borrar autos propios
    $car = Car::where('id', $id)->where('user_id', $user->id)->first();

    if (!$car) {
        return response()->json(['error' => 'No encontrado'], 404);
    }

    $car->delete();

    // restar 1
    $user->totalCars--;
    if ($user->totalCars < 0) $user->totalCars = 0;
    $user->save();

    return response()->json([
        'message' => 'Auto eliminado correctamente'
    ]);
}
public function recent()
{
    return response()->json([
        "cars" => Car::with("user")
            ->latest()
            ->take(2)
            ->get(),

        "users" => User::latest()
            ->take(2)
            ->get()
    ]);
}
// en CarController.php
public function featured() {
    return Car::with('user')->where('featured', true)->get();
}

}
