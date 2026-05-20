<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Car;
use Illuminate\Http\Request;
use App\Models\Report;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::count();

        $cars = Car::count();

        $reports = Report::count();

        $revenue = 125400;

        $recentCars = Car::with('user')
    ->latest()
    ->take(5)
    ->get()
    ->map(function ($car) {

        return [
            'id' => $car->id,
            'brand' => $car->brand,
            'model' => $car->model,
            'seller' => $car->user?->name ?? 'Sin vendedor',
            'created_at' => $car->created_at->diffForHumans(),
        ];
    });


        $recentUsers = User::latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'stats' => [
                'users' => $users,
                'cars' => $cars,
                'reports' => $reports,
                'revenue' => $revenue,
            ],
            'recentCars' => $recentCars,
            'recentUsers' => $recentUsers,
        ]);
    }
    public function users()
{
    $users = User::latest()->get()->map(function ($user) {

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'verified' => $user->verified,
            'totalCars' => Car::where('user_id', $user->id)->count(),
            'status' => 'activo',
        ];
    });

    return response()->json($users);
}
public function cars()
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
            'seller' => $car->user?->name ?? 'Sin vendedor',
            'status' => $car->status ?? 'pending',
            'createdAt' => $car->created_at->format('Y-m-d'),
        ];
    });

    return response()->json($cars);
}

public function updateCarStatus(Request $request, $id)
{
    $car = Car::findOrFail($id);

    $car->status = $request->status;

    $car->save();

    return response()->json([
        'message' => 'Estado actualizado'
    ]);
}
public function reports(Request $request)
{
    $token = $request->header('Authorization');

    $admin = User::where('api_token', $token)->first();

    if (!$admin || $admin->role !== 'admin') {

        return response()->json([
            'error' => 'No autorizado'
        ], 403);
    }

    $reports = Report::with(['car'])
        ->latest()
        ->get()
        ->map(function ($report) {

            return [

                'id' => $report->id,

                'reason' => $report->reason,

                'ip' => $report->ip,

'car' => $report->car
    ? $report->car->brand . ' ' . $report->car->model
    : 'Auto eliminado',

'car_id' => $report->car?->id,

                'created_at' => $report->created_at
                    ->format('d/m/Y H:i'),
            ];
        });

    return response()->json($reports);
}
}