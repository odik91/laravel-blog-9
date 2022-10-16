<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\SubmenuController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// route admin submenu mangement
Route::resource('submenu', SubmenuController::class);
Route::get('trash/submenu', [SubmenuController::class, 'trash'])->name('submenu.trash');
Route::get('trash/restore-submenu/{id}', [SubmenuController::class, 'restore'])->name('submenu.restore');
Route::delete('trash/delete-submenu/{id}', [SubmenuController::class, 'delete'])->name('submenu.delete');

// route admin category management
Route::resource('category', CategoryController::class);
Route::get('trash/category', [CategoryController::class, 'trash'])->name('category.trash');
Route::get('trash/restore-category/{id}', [CategoryController::class, 'restore'])->name('category.restore');
Route::delete('trash/delete-category/{id}', [CategoryController::class, 'delete'])->name('category.delete');

// route admin subcategory management
Route::resource('subcategory', SubcategoryController::class);
Route::get('trash/subcategory', [SubcategoryController::class, 'trash'])->name('subcategory.trash');
Route::get('trash/restore-subcategory/{id}', [SubcategoryController::class, 'restore'])->name('subcategory.restore');
Route::delete('trash/delete-subcategory/{id}', [SubcategoryController::class, 'delete'])->name('subcategory.delete');
