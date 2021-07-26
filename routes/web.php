<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\MyAuthController;
use App\Http\Controllers\AccountController;

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
Route::get('/', function () { return view('welcome'); });
Route::get('home', [HomeController::class, 'index'])->name('home');

Route::get('login', [MyAuthController::class, 'index'])->name('login');
Route::post('custom-login', [MyAuthController::class, 'customLogin'])->name('login.custom'); 
Route::get('registration', [MyAuthController::class, 'registration'])->name('register-user');
Route::get('custom-registration', [MyAuthController::class, 'customRegistration'])->name('register.custom');
Route::post('custom-registration', [MyAuthController::class, 'customRegistration'])->name('register.custom'); 

Route::get('forget-password', [ForgotPasswordController::class, 'getEmail']);
Route::post('forget-password', [ForgotPasswordController::class, 'postEmail']);
Route::get('reset-password/{token}', [ResetPasswordController::class, 'getPassword']);
Route::post('reset-password', [ResetPasswordController::class, 'updatePassword']);


/* solo super-admin puede administrar roles y usuarios */
   Route::group(['middleware' => ['role:super-admin']], function () {
    Route::resource('accounts', AccountController::class);
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);    
});

 
Route::group(['middleware' => ['role:super-admin|bank-user']], function () {
  
    Route::get('accounts', [AccountController::class, 'index'])->name('accounts');
    Route::get('transferir', [AccountController::class, 'transferir'])->name('transferir');
    Route::post('transferir', [AccountController::class, 'transferir'])->name('transferir');
    
    Route::get('transferencia', [AccountController::class, 'transferencia'])->name('transferencia');
    Route::post('transferencia', [AccountController::class, 'transferencia'])->name('transferencia');
    
    Route::get('transferir-tereceros', [AccountController::class, 'transferir_tereceros'])->name('transferir_tereceros');
    Route::post('transferir-tereceros', [AccountController::class, 'transferir_tereceros'])->name('transferir_tereceros');

    Route::get('matricular-cuenta', [AccountController::class, 'matricular_cuenta'])->name('matricular_cuenta');
    Route::post('matricular-cuenta', [AccountController::class, 'matricular_cuenta'])->name('matricular_cuenta'); 

    Route::get('listar-transferencias', [AccountController::class, 'listar_transferencias'])->name('listar_transferencias');
    Route::post('listar-transferencias', [AccountController::class, 'listar_transferencias'])->name('listar_transferencias');
    
    Route::post('matricular', [AccountController::class, 'matricular'])->name('matricular');
});

