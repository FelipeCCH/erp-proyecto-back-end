<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos'; // Nombre de la tabla en la base de datos

    // Define la clave primaria personalizada, ya que en tu tabla se llama 'id_producto'
    protected $primaryKey = 'id_producto';

    // Habilita la asignación masiva para los campos que deseas
    protected $fillable = [
        'id_empresa',
        'id_categoriaProducto',
        'nombre',
        'descripcion',
        'precio',
        'categoria',
        'disponible'
    ];

    /**
     * Relación con la empresa (proveedor).
     * Asumiendo que la tabla Empresa tiene la clave primaria 'id_empresa'.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }

   
}
