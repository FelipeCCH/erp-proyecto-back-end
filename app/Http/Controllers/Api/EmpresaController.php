<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;



class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            // Se obtienen todas las empresas
            $empresas = empresa::all();

        // Se retorna la respuesta en formato JSON
        return response()->json([
            'success' => true,
            'data' => $empresas
        ], 200);
        }catch (Exception $e){
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las empresas',
                'error'   => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    /**
 * Crea una nueva empresa en la base de datos.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\JsonResponse
 */
    public function store(Request $request)
    {
        try {
            // Validar los datos entrantes
            $validatedData = $request->validate([
                'cedula_juridica'    => 'required|string|max:20|unique:Empresa,cedula_juridica',
                'nombre'             => 'required|string|max:255',
                'correo'             => 'required|email|max:255',
                'tipo'               => 'required|in:Micro,Pequeña,Mediana,Grande',
                'ubicacionProvincia' => 'required|string|max:255',
                'ubicacionCanton'    => 'required|string|max:255',
                'ubicacionDistrito'  => 'required|string|max:255',
                'telefono'           => 'nullable|string|max:20',
                'estado'             => 'nullable|in:Pendiente,Aprobada,Rechazada',
            ]);

            // Establecer valores por defecto
            $validatedData['estado'] = $validatedData['estado'] ?? 'Pendiente';
            
            // Crear la empresa
            $empresa = Empresa::create($validatedData);

            // Retornar respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Empresa creada exitosamente',
                'data'    => $empresa
            ], 201);
        } catch (ValidationException $e) {
            // Capturar errores de validación
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $e->errors()
            ], 422);
        } catch (Exception $e) {
            // Capturar cualquier otro error
            Log::error('Error al crear empresa: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la empresa',
                'error'   => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * * Muestra la información de una empresa específica.
     *
     * @param  string  $id_empresa
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function show(string $id_empresa)
    {
        try {
            // Se busca la empresa por su ID. Si no existe, findOrFail lanzará una excepción.
            $empresa = Empresa::findOrFail($id_empresa);

            // Se retorna la respuesta exitosa con el registro encontrado.
            return response()->json([
                'success' => true,
                'data'    => $empresa
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Captura el error cuando no se encuentra la empresa y retorna una respuesta 404.
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada'
            ], 404);
        } catch (Exception $e) {
            // Registra el error para propósitos de depuración y retorna una respuesta 500.
            Log::error('Error al obtener la empresa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error'   => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Actualiza la información de una empresa existente.
     */
    public function update(Request $request, string $id)
    {
        //
        try {
            # Buscamos la empresa por su ID. Si no existe, findOrFail lanzará una excepción.
            $empresa = Empresa::findOrFail($id);

            $empresa->update($request->all());
            // Se retorna la respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Empresa actualizada exitosamente',
                'data'    => $empresa
            ], 200);
        } catch (ValidationException $e) {
            // Captura errores de validación
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors'  => $e->errors()
            ], 422);
        } catch (ModelNotFoundException $e) {
            // Captura el error cuando no se encuentra la empresa y retorna una respuesta 404.
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada'
            ], 404);
        } catch (Exception $e) {
            // Registra el error para propósitos de depuración y retorna una respuesta 500.
            Log::error('Error al actualizar la empresa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error'   => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {   
            // Se busca la empresa por su ID. Si no existe, findOrFail lanzará una excepción.
            $empresa = Empresa::findOrFail($id);

            // Se elimina la empresa
            $empresa->delete();

            // Se retorna la respuesta exitosa
            return response()->json([
                'success' => true,
                'message' => 'Empresa eliminada exitosamente'
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Captura el error cuando no se encuentra la empresa y retorna una respuesta 404.
            return response()->json([
                'success' => false,
                'message' => 'Empresa no encontrada'
            ], 404);
        } catch (Exception $e) {
            // Registra el error para propósitos de depuración y retorna una respuesta 500.
            Log::error('Error al eliminar la empresa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error'   => config('app.debug') ? $e->getMessage() : 'Error interno del servidor'
            ], 500);
        }
    }
}
