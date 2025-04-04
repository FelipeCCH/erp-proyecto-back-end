<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaProducto extends Model
{
    //use HasFactory;

    // Especifica el nombre de la tabla si no sigue la convención plural de Laravel.
    protected $table = 'CategoriaProducto';

    // Clave primaria personalizada.
    protected $primaryKey = 'id_categoriaProducto';

    // Deshabilitar timestamps si no se usan.
    public $timestamps = false;

    // Asignación masiva de atributos.
    protected $fillable = [
        'id_producto',
        'imagen',
    ];

    /**
     * Relación: CategoriaProducto pertenece a un Producto.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
