<?php

use App\Models\WorkorderTimesheets;
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


Auth::routes();

Route::get('/maintenance', 'Controller@show_maintenance')->name('maintenance');

Route::prefix('/')
    ->middleware('auth')
    ->group(__DIR__.'/auth_user.php');
