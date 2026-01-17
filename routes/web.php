<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReferralRedirectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/r/{code}', [ReferralRedirectController::class, 'redirect'])
    ->where('code', '[A-Za-z0-9_-]+')
    ->middleware('throttle:referral');
