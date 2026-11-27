<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ProductController extends Controller
{
    // Listar todos los productos registrados
    public function index()
    {
        try {
            // Consultar todos los productos mediante Query Builder
            $productos = DB::table('productos')->get();

            // Devolver los productos en formato JSON
            return response()->json([
                'success' => true,
                'message' => 'Productos obtenidos correctamente.',
                'data' => $productos
            ], 200);
        } catch (Exception $e) {
            // Registrar el detalle del error en Laravel
            Log::error('Error al obtener productos: ' . $e->getMessage());

            // Devolver una respuesta segura al cliente
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }
    // Registrar un nuevo producto
    public function store(StoreProductRequest $request)
    {
        try {
            // Obtener únicamente los datos que pasaron la validación
            $datos = $request->validated();

            // Insertar el producto y obtener su identificador
            $id = DB::table('productos')->insertGetId([
                'name' => $datos['name'],
                'description' => $datos['description'] ?? null,
                'price' => $datos['price'],
                'stock' => $datos['stock'],
                'status' => $datos['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Consultar el producto recién registrado
            $producto = DB::table('productos')->where('id', $id)->first();

            // Devolver el producto creado
            return response()->json([
                'success' => true,
                'message' => 'Producto registrado correctamente.',
                'data' => $producto
            ], 201);
        } catch (Exception $e) {

            // Registrar el detalle del error en Laravel
            Log::error('Error al registrar producto' . $e->getMessage());

            // Devolver una respuesta segura al cliente
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }

    // Consultar un producto por su identificador
    public function show($id)
    {
        try {
            // Buscar el producto según su ID
            $producto = DB::table('productos')
                ->where('id', $id)
                ->first();

            // Verificar si el producto existe
            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado.'
                ], 404);
            }

            // Devolver el producto encontrado
            return response()->json([
                'success' => true,
                'message' => 'Producto obtenido correctamente.',
                'data' => $producto
            ], 200);
        } catch (Exception $e) {
            // Registrar el detalle del error en Laravel
            Log::error('Error al obtener producto ' . $e->getMessage());

            // Devolver una respuesta segura al cliente
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }
    // Actualizar un producto existente
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            // Buscar el producto por su identificador
            $producto = DB::table('productos')
                ->where('id', $id)
                ->first();

            // Verificar si existe el producto
            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado.'
                ], 404);
            }

            // Obtener únicamente los datos validados
            $datos = $request->validated();

            // Agregar la fecha de actualización
            $datos['updated_at'] = now();

            // Actualizar los campos enviados
            DB::table('productos')
                ->where('id', $id)
                ->update($datos);

            // Consultar el producto actualizado
            $productoActualizado = DB::table('productos')
                ->where('id', $id)
                ->first();

            // Devolver el producto actualizado
            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado correctamente.',
                'data' => $productoActualizado
            ], 200);
        } catch (Exception $e) {

            // Registrar el detalle del error en Laravel
            Log::error('Error al actualizar producto' . $e->getMessage());

            // Devolver una respuesta segura al cliente
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }
    // Eliminar un producto por su identificador
    public function destroy($id)
    {
        try {
            // Buscar el producto antes de eliminarlo
            $producto = DB::table('productos')
                ->where('id', $id)
                ->first();

            // Verificar si el producto existe
            if (!$producto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado.'
                ], 404);
            }

            // Eliminar el producto de la base de datos
            DB::table('productos')
                ->where('id', $id)
                ->delete();

            // Devolver confirmación de eliminación
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado correctamente.'
            ], 200);
        } catch (Exception $e) {

            // Registrar el detalle del error en Laravel
            Log::error('- Error al eliminar producto ' . $e->getMessage());

            // Devolver una respuesta segura al cliente
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor.'
            ], 500);
        }
    }
}
