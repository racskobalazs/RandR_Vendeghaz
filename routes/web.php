<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArakController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\FooldalController;
use App\Http\Controllers\FoglalasController;
use App\Http\Controllers\HazirendController;
use App\Http\Controllers\KapcsolatController;
use App\Http\Controllers\ProgramokController;
use App\Http\Controllers\AdatvedelemController;

use App\Http\Controllers\FooldalEngController;
use App\Http\Controllers\ArakEngController;
use App\Http\Controllers\HazirendEngController;
use App\Http\Controllers\FoglalasEngController;


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

//for registered
/*Route::group(['middleware' => ['auth']], function() {
    Route::get('/dashboard', 'App\Http\Controllers\DashboardController@index')->name('dashboard');
});
*/
// for users
Route::group(['middleware' => ['auth', 'role:user']], function() {
    Route::get('/dashboard/myprofile', 'App\Http\Controllers\DashboardController@myprofile')->name('dashboard.myprofile');
    Route::get('/dashboard/adatmodositas', 'App\Http\Controllers\DashboardController@adatmodositas')->name('dashboard.adatmodositas');
    Route::get('/dashboard/vendegkonyv', 'App\Http\Controllers\DashboardController@vendegkonyv')->name('dashboard.vendegkonyv');

    Route::post('/dashboard/vendegkonyv', 'App\Http\Controllers\DashboardController@vendegkonyvsave')->name('dashboard.vendegkonyv');
    Route::post('/dashboard/adatmodositas', 'App\Http\Controllers\DashboardController@adatupdate')->name('dashboard.adatupdate');
});

// for admins
Route::group(['middleware' => ['auth', 'role:admin']], function() {
    Route::get('/dashboard/foglalasok', 'App\Http\Controllers\AdminController@foglalasok')->name('dashboard.foglalasok');
    Route::get('/dashboard/felhasznalok', 'App\Http\Controllers\AdminController@felhasznalok')->name('dashboard.felhasznalok');
    Route::get('/dashboard/kepfeltoltes', 'App\Http\Controllers\AdminController@kepfeltoltes_get')->name('dashboard.kepfeltoltes');
    Route::get('/dashboard/adminadatmodositas', 'App\Http\Controllers\AdminController@adminadatmodositas')->name('dashboard.adminadatmodositas');
    Route::get('/dashboard/ar', 'App\Http\Controllers\AdminController@arShow')->name('dashboard.armodositas');
    Route::get('/dashboard/oldalakSzerkesztese', 'App\Http\Controllers\AdminController@saveEditPages')->name('dashboard.oldalakSzerkesztese');
    Route::get('/dashboard/kepmodositas', 'App\Http\Controllers\AdminController@kepekListazasa')->name('dashboard.kepmodositas');
    Route::get('/dashboard/programmodositas', 'App\Http\Controllers\AdminController@programokListazasa')->name('dashboard.programmodositas');
    Route::get('/dashboard/vendegkonyv_lista', 'App\Http\Controllers\AdminController@vendegkonyv')->name('dashboard.vendegkonyv_lista');

    Route::post('/dashboard/adminadatmodositas', 'App\Http\Controllers\AdminController@adminadatupdate')->name('dashboard.adminadatupdate');
    Route::post('/dashboard/felhasznalok', 'App\Http\Controllers\AdminController@felhasznalupdate')->name('dashboard.felhasznalupdate');
    Route::post('/dashboard/foglalasok', 'App\Http\Controllers\AdminController@foglalasok_modositas')->name('dashboard.foglalasmodositas');
    Route::post('/dashboard/ar', 'App\Http\Controllers\AdminController@arakupdate')->name('dashboard.armodositas');
    Route::post('/dashboard/oldalakSzerkesztese', 'App\Http\Controllers\AdminController@saveEditPages')->name('dashboard.oldalakSzerkesztese');
    Route::post('/dashboard/kepfeltoltes', 'App\Http\Controllers\AdminController@kepfeltoltes')->name('dashboard.kepfeltoltes');
    Route::post('/dashboard/kepmodositas', 'App\Http\Controllers\AdminController@kepekSzerkesztese')->name('dashboard.kepmodositas');
    Route::post('/dashboard/programmodositas', 'App\Http\Controllers\AdminController@programokSzerkesztese')->name('dashboard.programmodositas');
    Route::post('/dashboard/export', 'App\Http\Controllers\AdminController@export')->name('dashboard.export');

});

require __DIR__.'/auth.php';

//basic access


Route::get('/', [FooldalController::class, 'index']);

Route::get('/fooldal', [FooldalController::class, 'index']);

Route::get('/fooldal', [FooldalController::class, 'index']);

Route::get('/galeria', [FooldalController::class, 'index']);

Route::get('/foglalas', [FoglalasController::class, 'index']);

Route::post('/foglalas', [FoglalasController::class, 'sendmail']);

Route::get('/hazirend', [HazirendController::class, 'index']);

Route::get('/programok', [ProgramokController::class, 'index']);

Route::get('/kapcsolat', [KapcsolatController::class, 'index']);

Route::get('/adatvedelem', [AdatvedelemController::class, 'index']);

Route::get('/arak', [ArakController::class, 'index']);

Route::get('/main', [FooldalEngController::class, 'index']);
Route::get('/prices', [ArakEngController::class, 'index']);
Route::get('/rules', [HazirendEngController::class, 'index']);
Route::get('/booking', [FoglalasEngController::class, 'index']);
Route::post('/booking', [FoglalasEngController::class, 'sendmail']);
