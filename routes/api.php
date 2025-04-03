<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductosController;
use App\Http\Controllers\Api\EmpresaController;

Route::apiResource('empresa', EmpresaController::class);
