<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        // Crear el usuario
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'role'      => 'user', // Rol por defecto
            'verified'  => false,
            'totalCars' => 0,
        ]);

        // Generar token para login automático inmediato
        $token = bin2hex(random_bytes(32));
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'message' => 'Registro exitoso',
            'token'   => $token,
            'user'    => $user
        ], 201);
    }

    /**
     * Login de usuario
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        // Verificar usuario y contraseña
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Credenciales incorrectas'], 401);
        }

        // Generar y guardar el token (formato plano)
        $token = bin2hex(random_bytes(32));
        $user->api_token = $token;
        $user->save();

        return response()->json([
            'message' => 'Login exitoso',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    /**
     * Actualizar perfil
     */
    public function updateProfile(Request $request)
    {
        // Obtener el token directamente del header (sin Bearer)
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'Sesión inválida'], 401);
        }

        // Actualizar campos (solo los que vengan en el request)
$user->update($request->only([
    'name',
    'phone',
    'location',
    'bio',
    'curp',
    'rfc',
    'domicile',
    'ine',
    'verified'
]));

        return response()->json([
            'message' => 'Perfil actualizado',
            'user'    => $user->fresh() // Retornamos los datos frescos de la DB
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $token = $request->header('Authorization');
        
        $user = User::where('api_token', $token)->first();

        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function sendRecoveryEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }

    $token = Str::random(60);

    $user->reset_token = $token;
    $user->reset_token_expires_at = now()->addMinutes(30);
    $user->save();

$frontendUrl = request()->getSchemeAndHttpHost();

$link = "$frontendUrl/reset-password?token=$token";

    try {
        Mail::to($user->email)->send(new ResetPasswordMail($link));

        return response()->json([
            'message' => 'Correo enviado correctamente'
        ]);

    } catch (\Exception $e) {
        Log::error("Error correo: " . $e->getMessage());

        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
public function resetPassword(Request $request)
{
    try {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:6'
        ]);

        $user = User::where('reset_token', $request->token)->first();

        if (!$user) {
            return response()->json(['error' => 'Token inválido'], 400);
        }

        if (now()->greaterThan($user->reset_token_expires_at)) {
            return response()->json(['error' => 'Token expirado'], 400);
        }

        $user->password = Hash::make($request->password);
        $user->reset_token = null;
        $user->reset_token_expires_at = null;
        $user->save();

        return response()->json([
            'message' => 'Contraseña actualizada correctamente'
        ]);

    } catch (\Exception $e) {
        Log::error("Reset error: " . $e->getMessage());

        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
}
