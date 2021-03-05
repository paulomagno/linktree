<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Page\PageController;

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
Route::get('/',[HomeController::class,'index']);

/*
Route::prefix('/admin')->group(function(){
    Route::get('/',[AdminController::class,'index']);
});
*/

Route::group(['prefix' => 'admin'], function () {

    Route::get('/',[AdminController::class,'index']);

    Route::get('/login',[AdminController::class,'login'])->name('login');
    Route::post('/login',[AdminController::class,'loginAction']);
    Route::get('/logout',[AdminController::class,'logout']);

    Route::get('/register',[AdminController::class,'register']);
    Route::post('/register',[AdminController::class,'registerAction']);

    Route::get('/{slug}/links',[AdminController::class,'pageLinks']);
    Route::get('/{slug}/design',[AdminController::class,'pageDesign']);
    Route::get('/{slug}/stats',[AdminController::class,'pageStats']);

    Route::get('/linkorder/{linkid}/{pos}',[AdminController::class,'linkOrderUpdate']);

    Route::get('/{slug}/newlink', [AdminController::class,'newLink']);
    Route::post('/{slug}/newlink',[AdminController::class,'newLinkAction']);

    Route::get('/{slug}/editlink/{linkid}',[AdminController::class,'editLink']);
    Route::post('/{slug}/editlink/{linkid}',[AdminController::class,'editLinkAction']);

    Route::get('/{slug}/dellink/{linkid}',[AdminController::class,'delLink']);
});

Route::get('/{slug}',[PageController::class,'index']);

// Rotas com FallBack (Recomendado utilizar no final do arquivo de rotas)
Route::fallback(function(){
    return view('notfound');
});




