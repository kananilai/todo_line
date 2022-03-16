<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LineLoginController;
use App\Http\Controllers\LineMessengerController;
use App\Http\Controllers\TaskController;
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
    return view('top');
});

Auth::routes();

Route::get('/main', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/linelogin', [LineLoginController::class,'lineLogin'])->name('linelogin');
Route::get('/callback', [LineLoginController::class,'callback'])->name('callback');

// LINE メッセージ受信
Route::post('/line/webhook', [LineMessengerController::class,'webhook'])->name('line.webhook');


// LINE メッセージ送信用
Route::get('/line/message', [LineMessengerController::class ,'message']);
Route::get('/user',[LineMessengerController::class,'user']);



Route::get('/main',[TaskController::class,'index']);
Route::post('/add',[TaskController::class,'create']);
Route::post('/update',[TaskController::class,'update']);
Route::post('/delete',[TaskController::class,'delete']);
Route::post('/done',[TaskController::class,'done']);
// Route::get('/main',[TaskController::class,'done_task']);

Route::get('/logout',[LineLoginController::class,'logout'])->name('logout');
