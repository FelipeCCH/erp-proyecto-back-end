<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{

    // Especifica el nombre de la tabla
    protected $table = 'empresa';

    // Define la llave primaria
    protected $primaryKey = 'id_empresa';

    // Deshabilita el manejo automático de los timestamps (ya que la tabla solo tiene fecha_registro)
    public $timestamps = false;

    // Define los campos que pueden ser asignados masivamente
    protected $fillable = [
        'cedula_juridica',
        'nombre',
        'correo',
        'tipo',
        'ubicacionProvincia',
        'ubicacionCanton',
        'ubicacionDistrito',
        'telefono',
        'estado',
    ];

    // Opcionalmente, indica que 'fecha_registro' es una fecha
    protected $dates = ['fecha_registro'];
}
