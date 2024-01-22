<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardSBS;
use App\Http\Controllers\EntrySBSController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ValidasiSBSController;
use App\Http\Controllers\ValidasiSBSDinasController;
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
    return view('landingPage');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/register', function () {
    return view('register');
});
Route::post('login', [LoginController::class, 'authenticate'])->name('login');
Route::post('register', [LoginController::class, 'register'])->name('register');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Entry
Route::resource('/entry', EntrySBSController::class)->middleware('auth');
Route::post('/entry/storeMonthYearSelection', [EntrySBSController::class, 'storeMonthYearSelection'])->middleware('auth');
Route::get('/entry/pertLuas/{entry}', [EntrySBSController::class, 'pertLuas'])->middleware('auth');
Route::get('/entry/pertProd/{entry}', [EntrySBSController::class, 'pertProd'])->middleware('auth');

// Dashboard
Route::resource('/dashboard', DashboardSBS::class)->middleware('auth');
// users
Route::resource('/users', UsersController::class)->middleware('auth');
// Profile
Route::resource('/profile', ProfileController::class)->middleware('auth');

// Validasi Dinas
Route::resource('/validasi/dinas', ValidasiSBSDinasController::class)->middleware('auth');
// Validasi BPS
Route::resource('/validasi/bps', ValidasiSBSController::class)->middleware('auth');
// Admin
Route::resource('/admin/entrysbs', AdminController::class)->middleware('auth');
