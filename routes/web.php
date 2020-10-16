<?php

use App\Http\Controllers\data_surveyController;
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
    return view('content.home');
});
Route::get('pengolahan',[data_surveyController::class,'pengolahan']);
Route::get('penyajian',[data_surveyController::class,'penyajian']);
Route::post('add_data',[data_surveyController::class,'store']);
Route::post('edit_data',[data_surveyController::class,'update']);
Route::post('delete_data/{id}',[data_surveyController::class,'delete']);
