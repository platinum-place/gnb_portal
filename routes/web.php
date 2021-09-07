<?php

use App\Http\Controllers\Adjuntos;
use App\Http\Controllers\Buscar;
use App\Http\Controllers\Emisiones;
use App\Http\Controllers\Home;
use App\Http\Controllers\Reportes;
use App\Http\Controllers\Sesiones;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Paginas relacionadas a manejo de usuarios y sesiones
*/

Route::get('/login', [Sesiones::class, 'create'])->name("login");
Route::get('/ingresar', [Sesiones::class, 'create']);
Route::post('/login', [Sesiones::class, 'store']);
Route::get('/salir', [Sesiones::class, 'destroy']);
Route::get('/editar', [Sesiones::class, 'edit'])->middleware('sesion');
Route::post('/editar', [Sesiones::class, 'update'])->middleware('sesion');


/*
Paginas relacionas al manejo de arvhivos
*/
//generar reportes en excel
Route::get('/reportes', [Reportes::class, 'create'])->middleware('sesion');
Route::post('/reportes', [Reportes::class, 'store'])->middleware('sesion');
//descargar adjunto de las coberturas de una emision
Route::get('/condicionado/{id}', [Adjuntos::class, 'index'])->middleware('sesion');
//adjuntar documentos a una emision
Route::get('/adjuntar/{id}', [Adjuntos::class, 'create'])->middleware('sesion');
Route::post('/adjuntar', [Adjuntos::class, 'store'])->middleware('sesion');


/*
Paginas relacionas a emisiones
*/
Route::get('/buscar', [Buscar::class, 'index'])->middleware('sesion');
Route::get('/', Home::class)->name("home")->middleware('sesion');
Route::get('/emision/{id}', [Emisiones::class, 'index'])->middleware('sesion');




/*
//Inicio
Route::get('/', HomeController::class)->name("home")->middleware('sesion');


//Cotizar

//Buscar emisión
Route::get('/buscar', [BuscarController::class, 'index'])->middleware('sesion');
Route::get('/condicionado/{id}', [BuscarController::class, 'descargarDocumentos'])->middleware('sesion');
Route::get('/adjuntar/{id}', [BuscarController::class, 'adjuntarDocumento'])->middleware('sesion');
Route::post('/adjuntar', SubirDocumentos::class);
Route::get('/descargaremision/{id}', [BuscarController::class, 'descargaremision'])->middleware('sesion');
*/
