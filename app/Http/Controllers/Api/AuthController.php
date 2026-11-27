<?php

namespace App\Http\Controllers\Api;

// Dependencias necesarias para el controlador de autenticación

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Método encargado de registrar un nuevo usuario
    // RegisterRequest permite validar los datos recibidos antes de ejecutar el registro
    public function register(RegisterRequest $request)
    {
        try {

            // Insertar un nuevo usuario en la tabla users utilizando Query Builder
            // insertGetId() permite obtener el ID del usuario recién registrado
            $userId = DB::table('users')->insertGetId([

                // Obtener el nombre enviado en la solicitud
                'name' => $request->name,

                // Obtener el correo electrónico enviado en la solicitud
                'email' => $request->email,

                // Cifrar la contraseña antes de almacenarla en la base de datos
                // Hash::make() utiliza el algoritmo de hashing configurado por Laravel
                'password' => Hash::make($request->password),

                // Registrar la fecha y hora de creación del usuario
                'created_at' => now(),

                // Registrar la fecha y hora de actualización del usuario
                'updated_at' => now(),
            ]);

            // Devolver una respuesta JSON indicando que el registro fue exitoso
            // El código HTTP 201 significa que el recurso fue creado correctamente
            return response()->json([
                'message' => 'Usuario registrado exitosamente.',

                // Información del usuario registrado
                // No se devuelve la contraseña por motivos de seguridad
                'data' => [
                    'id' => $userId,
                    'name' => $request->name,
                    'email' => $request->email,
                ],
            ], 201);
        } catch (\Exception $e) {

            // Registrar el detalle del error en los registros de Laravel
            Log::error('Error al registrar usuario: ' . $e->getMessage());

            // Devolver una respuesta segura al cliente
            return response()->json([
                'message' => 'Error interno del servidor.',
            ], 500);
        }
    }
    // Método encargado de iniciar sesión y generar un token de autenticación para el usuario
    public function login(LoginRequest $request)
    {
        try {
            // Buscar al usuario por su correo electronico utilizando Query Builder
            $userData = DB::table('users')
                ->where('email', $request->email)
                ->first();

            //comprobar si el usuario existe 
            if (!$userData) {
                return response()->json([
                    'message' => 'Credenciales inválidas.',
                ], 401);
            }

            //verificar que la contraseña ingresada coincida con el hash almacenado en la base de datos
            if (!Hash::check($request
                ->password, $userData
                ->password)) {
                return response()->json([
                    'message' => 'Credenciales inválidas.',
                ], 401);
            }

            //crear una instancia del modelo User con los datos obtenidos
            //esto permite utilizar el metodo de sanctum para generar un token de acceso
            $user = new User();
            $user->setRawAttributes((array) $userData, true);


            // Generar un nuevo token de acceso para el usuario
            $token = $user->createToken('auth_token')->plainTextToken;

            // Devolver los datos del usuario y su token de autenticación
            return response()->json([
                'message' => 'Inicio de sesión exitoso.',
                'data' => [
                    'id' => $userData->id,
                    'name' => $userData->name,
                    'email' => $userData->email,
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            ], 200);
        } catch (\Exception $e) {

            // Registrar el detalle del error en los registros de Laravel
            Log::error('Error al iniciar sesión: ' . $e->getMessage());

            // Devolver una respuesta segura al cliente
            return response()->json([
                'message' => 'Error interno del servidor.',
            ], 500);
        }
    }

    // Método encargado de cerrar la sesión del usuario
    public function logout(Request $request)
    {
        try {

            // Obtener el token que está utilizando el usuario y eliminarlo
            $request->user()->currentAccessToken()->delete();

            // Devolver una respuesta indicando que la sesión se cerró correctamente
            return response()->json([
                'message' => 'Sesión cerrada correctamente.',
            ], 200);
        } catch (\Exception $e) {

            // Registrar el detalle del error en los registros de Laravel
            Log::error('Error al cerrar sesión: ' . $e->getMessage());

            // Devolver una respuesta segura al cliente
            return response()->json([
                'message' => 'Error interno del servidor.',
            ], 500);
        }
    }

    // Método encargado de obtener los datos del usuario autenticado
    public function profile(Request $request)
    {
        // Obtener el usuario autenticado mediante el token de Sanctum
        $user = $request->user();

        // Devolver los datos del usuario en formato JSON
        return response()->json([
            'message' => 'Perfil obtenido correctamente.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 200);
    }
}
