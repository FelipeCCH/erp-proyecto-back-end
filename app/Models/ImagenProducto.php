<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenProducto extends Model
{
    // Especifica el nombre de la tabla.
    protected $table = 'ImagenProducto';

    // Clave primaria personalizada.
    protected $primaryKey = 'id_imagenProducto';

    // Deshabilitar timestamps si no se utilizan.
    public $timestamps = false;

    // Asignación masiva de atributos.
    protected $fillable = [
        'id_producto',
        'imagen',
    ];

    /**
     * Relación: ImagenProducto pertenece a un Producto.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
