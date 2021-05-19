<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
use Illuminate\Http\Request;

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
    return view('index');
});

//Route::get('/index', [CalendarController::class,'index']);
Route::post("/index", "App\Http\Controllers\CalendarController@show");
Route::get('/create_calendar_index', [CalendarController::class,'create']);
