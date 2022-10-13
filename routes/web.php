<?php

use App\Http\Controllers\Admin\MenuController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// route admin menu management
Route::resource('menu', MenuController::class);
Route::get('trash/menu', [MenuController::class, 'trash'])->name('menu.trash');
Route::get('trash/restore-menu/{id}', [MenuController::class, 'restoreMenu'])->name('menu.restore');
Route::delete('trash/delete-menu/{id}', [MenuController::class, 'delete'])->name('menu.delete');