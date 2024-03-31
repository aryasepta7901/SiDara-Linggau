<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardSBS;
use App\Http\Controllers\EntryBSTController;
use App\Http\Controllers\EntrySBSController;
use App\Http\Controllers\EntryTBFController;
use App\Http\Controllers\EntryTHController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ValidasiBSTBPSController;
use App\Http\Controllers\ValidasiBSTDinasController;
use App\Http\Controllers\ValidasiDinasBSTController;
use App\Http\Controllers\ValidasiSBSController;
use App\Http\Controllers\ValidasiSBSDinasController;
use App\Http\Controllers\ValidasiTBFBPSController;
use App\Http\Controllers\ValidasiTBFDinasController;
use App\Http\Controllers\ValidasiTHBPSController;
use App\Http\Controllers\ValidasiTHDinasController;
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


Route::get('', function () {
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

// Dashboard
Route::resource('/dashboard', DashboardController::class)->middleware('auth');

// sbsEntry
Route::resource('/sbsentry', EntrySBSController::class)->middleware('auth');
Route::post('/sbsentry/storeMonthYearSelection', [EntrySBSController::class, 'storeMonthYearSelection'])->middleware('auth');
Route::post('/sbsentry/reset', [EntrySBSController::class, 'reset'])->middleware('auth');
Route::get('/sbsentry/pertLuas/{sbsentry}', [EntrySBSController::class, 'pertLuas'])->middleware('auth');
Route::get('/sbsentry/pertProd/{sbsentry}', [EntrySBSController::class, 'pertProd'])->middleware('auth');
// Validasi Dinas
Route::resource('/sbsvalidasi/dinas', ValidasiSBSDinasController::class)->middleware('auth');
// Validasi BPS
Route::resource('/sbsvalidasi/bps', ValidasiSBSController::class)->middleware('auth');


// tbfEntry
Route::resource('/tbfentry', EntryTBFController::class)->middleware('auth');
Route::post('/tbfentry/storeMonthYearSelection', [EntryTBFController::class, 'storeMonthYearSelection'])->middleware('auth');
Route::post('/tbfentry/reset', [EntryTBFController::class, 'reset'])->middleware('auth');
Route::get('/tbfentry/pertLuas/{tbfentry}', [EntryTBFController::class, 'pertLuas'])->middleware('auth');
Route::get('/tbfentry/pertProd/{tbfentry}', [EntryTBFController::class, 'pertProd'])->middleware('auth');

// Validasi 
Route::resource('/tbfvalidasi/dinas', ValidasiTBFDinasController::class)->middleware('auth');
Route::resource('/tbfvalidasi/bps', ValidasiTBFBPSController::class)->middleware('auth');



// bstentry
Route::resource('/bstentry', EntryBSTController::class)->middleware('auth');
Route::post('/bstentry/storeMonthYearSelection', [EntryBSTController::class, 'storeMonthYearSelection'])->middleware('auth');
Route::post('/bstentry/reset', [EntryBSTController::class, 'reset'])->middleware('auth');
Route::get('/bstentry/pertLuas/{bstentry}', [EntryBSTController::class, 'pertLuas'])->middleware('auth');
Route::get('/bstentry/pertProd/{bstentry}', [EntryBSTController::class, 'pertProd'])->middleware('auth');

// Validasi 
Route::resource('/bstvalidasi/dinas', ValidasiBSTDinasController::class)->middleware('auth');
Route::resource('/bstvalidasi/bps', ValidasiBSTBPSController::class)->middleware('auth');


// thentry
Route::resource('/thentry', EntryTHController::class)->middleware('auth');
Route::post('/thentry/storeMonthYearSelection', [EntryTHController::class, 'storeMonthYearSelection'])->middleware('auth');
Route::post('/thentry/reset', [EntryTHController::class, 'reset'])->middleware('auth');
Route::get('/thentry/pertLuas/{thentry}', [EntryTHController::class, 'pertLuas'])->middleware('auth');
Route::get('/thentry/pertProd/{thentry}', [EntryTHController::class, 'pertProd'])->middleware('auth');

// Validasi 
Route::resource('/thvalidasi/dinas', ValidasiTHDinasController::class)->middleware('auth');
Route::resource('/thvalidasi/bps', ValidasiTHBPSController::class)->middleware('auth');


// users
Route::resource('/users', UsersController::class)->middleware('auth');
// Profile
Route::resource('/profile', ProfileController::class)->middleware('auth');



// Admin
Route::resource('/admin/entrysbs', AdminController::class)->middleware('auth');



// Dashboard
// Route::resource('/dashboard', DashboardSBS::class)->middleware('auth');