<?php

use App\Http\Controllers\ControllerIclock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Iclock
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('cdata', [ControllerIclock::class, 'getCdata']);
Route::post('devicecmd', [ControllerIclock::class, 'postDevceCmd']);
Route::post('cdata', [ControllerIclock::class, 'postCdata']);
Route::get('getrequest', [ControllerIclock::class, 'getRequest']);
