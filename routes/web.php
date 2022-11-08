<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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
    return view('home');
});
Route::get('/results', [DashboardController::class, 'index']);
Route::get('/create', [DashboardController::class, 'create']);
Route::post('/returnresult', [DashboardController::class, 'show']);
Route::post('/store', [DashboardController::class, 'store']);
