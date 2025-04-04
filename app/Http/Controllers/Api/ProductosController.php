<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ImagenProducto;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class ProductosController extends Controller
{
    public function index()
    {
        try {
            // Obtenemos todos los productos y cargamos de manera ansiosa la relación 'imagenes'
            $productos = Producto::with('imagenes')->get();

            // Retornamos la respuesta exitosa en formato JSON
            return response()->json([
                'success' => true,
                'data'    => $productos
            ], 200);
        } catch (Exception $e) {
            Log::error('Error al obtener los productos: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error'   => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function store(Request $request)
    {
        try {
            // Validar los datos del producto y las imágenes (si se envían)
            $validatedData = $request->validate([
                'id_empresa'             => 'required|integer|exists:Empresa,id_empresa',
                'id_categoriaProducto'   => 'required|integer|exists:CategoriaProducto,id_categoriaProducto',
                'nombre'                 => 'required|string|max:255',
                'descripcion'            => 'nullable|string',
                'precio'                 => 'required|numeric',
                'categoria'              => 'required|string|max:100',
                'disponible'             => 'nullable|boolean',
                // Para múltiples imágenes, esperamos un arreglo
                'imagenes.*'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            // Crear el registro del producto
            $producto = Producto::create([
                'id_empresa'             => $validatedData['id_empresa'],
                'id_categoriaProducto'   => $validatedData['id_categoriaProducto'],
                'nombre'                 => $validatedData['nombre'],
                'descripcion'            => $validatedData['descripcion'] ?? null,
                'precio'                 => $validatedData['precio'],
                'categoria'              => $validatedData['categoria'],
                'disponible'             => $validatedData['disponible'] ?? true,
            ]);

            // Si se envían imágenes, se procesan
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $image) {
                    // Almacenar cada imagen en la carpeta 'productos' del disco 'public'
                    $path = $image->store('productos', 'public');

                    // Crear un registro en ImagenProducto para cada imagen
                    ImagenProducto::create([
                        'id_producto' => $producto->id_producto,
                        'imagen'      => $path,
                    ]);
                }
            }

            // Retornar la respuesta exitosa, cargando la relación 'imagenes'
            return response()->json([
                'success' => true,
                'message' => 'Producto creado exitosamente',
                'data'    => $producto->load('imagenes'),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            Log::error('Error al crear producto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error'   => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Buscar el producto por su ID y cargar la relación 'imagenes'
            $producto = Producto::with('imagenes')->findOrFail($id);

            // Retornar la respuesta exitosa con el producto encontrado
            return response()->json([
                'success' => true,
                'data'    => $producto
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Capturar el error cuando no se encuentra el producto y retornar un código 404
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        } catch (Exception $e) {
            // Registrar el error para propósitos de depuración y retornar una respuesta 500
            Log::error('Error al obtener el producto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error'   => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }


    public function update(Request $request, string $id)
    {
        try {
            // Buscar el producto y cargar la relación 'imagenes'
            $producto = Producto::with('imagenes')->findOrFail($id);

            // Validar los datos enviados
            $validatedData = $request->validate([
                'id_empresa'             => 'required|integer|exists:Empresa,id_empresa',
                'id_categoriaProducto'   => 'required|integer|exists:CategoriaProducto,id_categoriaProducto',
                'nombre'                 => 'required|string|max:255',
                'descripcion'            => 'nullable|string',
                'precio'                 => 'required|numeric',
                'categoria'              => 'required|string|max:100',
                'disponible'             => 'nullable|boolean',
                // Permitir múltiples imágenes para agregar
                'imagenes.*'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            // Actualizar los datos del producto
            $producto->update([
                'id_empresa'             => $validatedData['id_empresa'],
                'id_categoriaProducto'   => $validatedData['id_categoriaProducto'],
                'nombre'                 => $validatedData['nombre'],
                'descripcion'            => $validatedData['descripcion'] ?? $producto->descripcion,
                'precio'                 => $validatedData['precio'],
                'categoria'              => $validatedData['categoria'],
                'disponible'             => $validatedData['disponible'] ?? $producto->disponible,
            ]);

            // Si se envían nuevas imágenes, agregarlas
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $image) {
                    // Almacenar cada imagen en la carpeta 'productos' del disco 'public'
                    $path = $image->store('productos', 'public');

                    // Crear un registro en ImagenProducto para la imagen
                    ImagenProducto::create([
                        'id_producto' => $producto->id_producto,
                        'imagen'      => $path,
                    ]);
                }
            }

            // Retornar la respuesta exitosa, cargando la relación 'imagenes'
            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado exitosamente',
                'data'    => $producto->load('imagenes'),
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $e->errors(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado',
            ], 404);
        } catch (Exception $e) {
            Log::error('Error al actualizar producto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error'   => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    public function destroyImage(string $id_producto, string $id_imagen)
    {
        try {
            // Buscar la imagen que pertenezca al producto dado
            $imagenProducto = ImagenProducto::where('id_imagenProducto', $id_imagen)
                ->where('id_producto', $id_producto)
                ->firstOrFail();

            // Eliminar el archivo de la imagen del disco 'public'
            Storage::disk('public')->delete($imagenProducto->imagen);

            // Eliminar el registro de la imagen en la base de datos
            $imagenProducto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Imagen eliminada exitosamente'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Imagen o producto no encontrado'
            ], 404);
        } catch (Exception $e) {
            Log::error('Error al eliminar imagen: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error'   => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }


    public function destroy(string $id)
    {
        try {   
            // Buscar el producto y cargar la relación 'imagenes'
            $producto = Producto::with('imagenes')->findOrFail($id);

            // Eliminar cada imagen asociada: borrar archivo y registro en la DB
            foreach ($producto->imagenes as $imagen) {
                Storage::disk('public')->delete($imagen->imagen);
                $imagen->delete();
            }

            // Eliminar el producto
            $producto->delete();

            // Retornar la respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Producto y sus imágenes eliminados exitosamente'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        } catch (Exception $e) {
            Log::error('Error al eliminar producto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error'   => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

}
