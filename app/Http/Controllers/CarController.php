<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
            'images.*'     => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);
// guardar imágenes
$imagePaths = [];

if ($request->file('images')) {

    return response()->json([
        'cloudinary' => env('CLOUDINARY_URL'),
    ]);

    foreach ($request->file('images') as $image) {

        $uploadedFileUrl = Cloudinary::upload(
            $image->getRealPath(),
            [
                'folder' => 'cars'
            ]
        )->getSecurePath();

        $imagePaths[] = $uploadedFileUrl;
    }
}
try {

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
        'images'       => $imagePaths,
    ]);

} catch (\Exception $e) {

return response()->json([
    'error' => $e->getMessage(),
    'line' => $e->getLine(),
], 500);
}

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
        'sellerName'  => $car->user?->name,
        'sellerPhone' => $car->user?->phone,
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

public function filter(Request $request)
{
    $query = Car::query();

    // 🔍 BUSCADOR
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('brand', 'like', "%{$request->search}%")
              ->orWhere('model', 'like', "%{$request->search}%");
        });
    }

    // 🚗 MARCA
    if ($request->brand) {
        $query->where('brand', $request->brand);
    }

    // 💰 PRECIO
    if ($request->price == 'low') {
        $query->where('price', '<=', 200000);
    }

    if ($request->price == 'mid') {
        $query->whereBetween('price', [200000, 300000]);
    }

    if ($request->price == 'high') {
        $query->where('price', '>=', 300000);
    }

    // 🔃 ORDEN
    if ($request->order == 'price_asc') {
        $query->orderBy('price', 'asc');
    }

    if ($request->order == 'price_desc') {
        $query->orderBy('price', 'desc');
    }

    if ($request->order == 'year') {
        $query->orderBy('year', 'desc');
    }

    if ($request->order == 'km') {
        $query->orderBy('mileage', 'asc');
    }

    // default
    if (!$request->order || $request->order == 'latest') {
        $query->latest();
    }

    return response()->json($query->get());
}
public function adminCars()
{
    $cars = Car::with('user')
        ->latest()
        ->get()
        ->map(function ($car) {

            return [
                'id' => $car->id,
                'brand' => $car->brand,
                'model' => $car->model,
                'year' => $car->year,
                'price' => $car->price,
                'seller' => $car->user?->name ?? 'Sin usuario',
                'status' => $car->status ?? 'pending',
                'createdAt' => $car->created_at->format('Y-m-d'),
            ];
        });

    return response()->json($cars);
}

public function updateStatus(Request $request, $id)
{
    $car = Car::find($id);

    if (!$car) {
        return response()->json([
            'error' => 'Auto no encontrado'
        ], 404);
    }

    $request->validate([
        'status' => 'required|in:pending,approved,rejected'
    ]);

    $car->status = $request->status;

    $car->save();

    return response()->json([
        'message' => 'Estado actualizado',
        'car' => $car
    ]);
}
}
