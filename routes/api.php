<?php

use App\Http\Controllers\categoriasController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\EmpresasController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductosCatController;
use App\Http\Controllers\ProductosChecksController;
use App\Http\Controllers\ProveedoresController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('producto/index', [ProductController::class, 'index']);
Route::get('producto_checks/index', [ProductosChecksController::class, 'index']);
Route::get('producto/show/{id}', [ProductController::class, 'show']);
Route::get('empresa/index', [EmpresasController::class, 'index']);
Route::get('empresa/show/{id}', [EmpresasController::class, 'show']);
Route::get('proveedor/index', [ProveedoresController::class, 'index']);
Route::get('proveedor/show/{id}', [ProveedoresController::class, 'show']);
Route::get('categorias/index', [categoriasController::class, 'index']);
Route::get('categorias/show/{id}', [categoriasController::class, 'show']);
Route::get('productocat/index', [ProductosCatController::class, 'index']);
Route::get('productocat/show/{id}', [ProductosCatController::class, 'show']);
Route::get('empleado/index', [EmpleadosController::class, 'index']);
Route::get('empleado/show/{id}', [EmpleadosController::class, 'show']);
Route::get('cliente/index', [ClientesController::class, 'index']);
Route::get('cliente/show/{id}', [ClientesController::class, 'show']);

Route::get('/vista', [ProductController::class, 'vista']);

Route::post('productos/store', [ProductController::class, 'store']);
Route::post('productos/update', [ProductController::class, 'update']);
Route::post('productos/destroy/{id}', [ProductController::class, 'destroy']);

Route::post('proveedor/store', [ProveedoresController::class, 'store']);
Route::post('proveedor/update', [ProveedoresController::class, 'update']);
Route::post('proveedor/destroy/{id}', [ProveedoresController::class, 'destroy']);

Route::post('empresa/store', [EmpresasController::class, 'store']);
Route::post('empresa/update', [EmpresasController::class, 'update']);
Route::post('empresa/destroy/{id}', [EmpresasController::class, 'destroy']);

Route::post('categorias/store', [categoriasController::class, 'store']);
Route::post('categorias/update', [categoriasController::class, 'update']);
Route::post('categorias/destroy/{id}', [categoriasController::class, 'destroy']);

Route::post('productocat/store', [ProductosCatController::class, 'store']);
Route::post('productocat/update', [ProductosCatController::class, 'update']);
Route::post('productocat/destroy/{id}', [ProductosCatController::class, 'destroy']);
Route::post('productocat/BuscarPorCat', [ProductosCatController::class, 'BuscarPorCat']);

Route::post('empleado/store', [EmpleadosController::class, 'store']);
Route::post('empleado/update', [EmpleadosController::class, 'update']);
Route::post('empleado/destroy/{id}', [EmpleadosController::class, 'destroy']);

Route::post('producto_checks/store', [ProductosChecksController::class, 'store']);
Route::post('producto_checks/update', [ProductosChecksController::class, 'update']);
Route::post('producto_checks/destroy/{id}', [ProductosChecksController::class, 'destroy']);

Route::post('cliente/store', [ClientesController::class, 'store']);
Route::post('cliente/update', [ClientesController::class, 'update']);
Route::post('cliente/destroy/{id}', [ClientesController::class, 'destroy']);

Route::middleware('auth:sanctum')->group(function () {
});

