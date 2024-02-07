<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\AnomaliaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class ,'welcome'])->name('welcome');
Route::post('/', [HomeController::class ,'welcome'])->name('welcome');

Route::get('/reservar-libro/{id}', [LibroController::class, 'reservarLibro'])->name('reservaLibro');

Route::get('/libros-reservados', [LibroController::class, 'libros_reservados'])->name('libros-reservados');

Route::get('/cancelaRecogida/{id}', [LibroController::class, 'cancelaRecogida'])->name('cancelaRecogida');

Route::get('/gestionReservas', [LibroController::class, 'gestionReservas'])->name('gestionReservas');

Route::get('/confirmarEntrega/{id}', [LibroController::class, 'confirmarEntrega'])->name('confirmarEntrega');

Route::get('/confirmarRecogida/{id}', [LibroController::class, 'confirmarRecogida'])->name('confirmarRecogida');
Route::post('/confirmarRecogida/{id}', [LibroController::class, 'confirmarRecogida'])->name('confirmarRecogida');

Route::get('/gestionLibros', [LibroController::class, 'gestionLibros'])->name('gestionLibros');

Route::get('/eliminarLibro/{id}', [LibroController::class, 'eliminarLibro'])->name('eliminarLibro');

Route::get('/formEditarLibro/{id}', [LibroController::class, 'formEditarLibro'])->name('formEditarLibro');
Route::post('/formEditarLibro/{id}', [LibroController::class, 'formEditarLibro'])->name('formEditarLibro');

Route::get('/addLibro', [LibroController::class, 'addLibro'])->name('addLibro');
Route::post('/addLibro', [LibroController::class, 'addLibro'])->name('addLibro');

Route::get('/muestraAnomalias', [AnomaliaController::class, 'muestraAnomalias'])->name('muestraAnomalias');

Route::get('/editarPerfil', [HomeController::class, 'editarPerfil'])->name('editarPerfil');
Route::post('/editarPerfil', [HomeController::class, 'editarPerfil'])->name('editarPerfil');



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
