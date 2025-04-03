<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function store(Request $request)
    {
        // Validar los datos recibidos en la solicitud
        $data = $request->validate([
            'id_empresa'  => 'required|exists:empresas,id_empresa',
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio'      => 'nullable|numeric',
            'categoria'   => 'nullable|string|max:100',
            'disponible'  => 'sometimes|boolean'
        ]);

        // Crear el producto en la base de datos usando Eloquent
        $producto = Producto::create($data);

        // Devolver una respuesta JSON indicando que el producto fue creado exitosamente
        return response()->json([
            'message'  => 'Producto creado exitosamente',
            'producto' => $producto
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
