<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToDoController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[ToDoController::class,'index']);
Route::post('/addtask',[ToDoController::class,'addTask']);
Route::get('/getalltask',[ToDoController::class,'getallTask']);
Route::post('/deletetask',[ToDoController::class,'deleteTask']);
Route::post('/taskcompleted',[ToDoController::class,'taskCompleted']);
Route::get('/showalltask',[ToDoController::class,'showallTask']);
