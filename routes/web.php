<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\PosisiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JenisUnitController;
use App\Http\Controllers\SuplierController;
use App\Http\Controllers\Karyawan\KaryawanController;
use App\Http\Controllers\MaintananceController;
use App\Http\Controllers\MaintenanceScheduleController;

use App\Http\Controllers\pembelianController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\UnitController;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Prefix untuk semua route karyawan
    Route::prefix('karyawan')->group(function () {
        Route::get('/form', [KaryawanController::class, 'index'])->name('input_karyawan');
        Route::get('/data', [KaryawanController::class, 'data'])->name('data_karyawan');
        Route::post('/store', [KaryawanController::class, 'store'])->name('store_karyawan');
        Route::get('/edit/{id}', [KaryawanController::class, 'edit'])->name('karyawan_edit');
        Route::put('/update/{id}', [KaryawanController::class, 'update'])->name('karyawan_update');
        Route::delete('/destroy/{id}', [KaryawanController::class, 'destroy'])->name('karyawan_destroy');
    });
    //Resource untuk user
    Route::resource('user', UserController::class);

    // Resource untuk Departemen & Posisi
    route::resource('departemen',DepartemenController::class);
    route::resource('Posisi',PosisiController::class);

    //Resource untuk Jenis unit
    Route::resource('jenis-unit', JenisUnitController::class)->except('show');
    Route::resource('Maintanance', MaintananceController::class);
    //Resource untuk Suplier
    Route::resource('supplier', SuplierController::class);
    Route::resource('pengadaan-sparepart', pembelianController::class);
    //Resource untuk Sparepart
    Route::resource('sparepart', SparepartController::class)->except(['show', 'edit', 'update']);
    //resource untuk proyek
    route::resource('proyek', \App\Http\Controllers\ProyekController::class);
    Route::get('/proyek/{id}/addendum', [\App\Http\Controllers\ProyekController::class, 'addendum'])->name('proyek.addendum');
    Route::post('/proyek/{id}/addendum/store', [\App\Http\Controllers\ProyekController::class, 'storeAddendum'])->name('proyek.addendum.store');
    Route::get('/proyek/{id}/addendum/{addendumId}', [\App\Http\Controllers\ProyekController::class, 'showAddendum'])->name('addendum.show');
    Route::get('/proyek/{id}/addendum/{addendumId}/edit', [\App\Http\Controllers\ProyekController::class, 'editAddendum'])->name('addendum.edit');
    Route::put('/proyek/{id}/addendum/{addendumId}', [\App\Http\Controllers\ProyekController::class, 'updateAddendum'])->name('addendum.update');
    Route::delete('/proyek/{id}/addendum/{addendumId}', [\App\Http\Controllers\ProyekController::class, 'destroyAddendum'])->name('addendum.destroy');


    route::resource('Mutasi-Unit', \App\Http\Controllers\MutasiUnitController::class)   ;
    Route::prefix('unit')->group(function () {
    Route::get('/', [UnitController::class, 'index'])->name('data_Unit');
    Route::get('/lama', [UnitController::class, 'index'])->name('unit_lama');
    Route::get('/create', [UnitController::class, 'create'])->name('create_Unit');
    Route::post('/store', [UnitController::class, 'store'])->name('store_Unit');
    Route::post('/pemilikik_store', [UnitController::class, 'pemilik_store'])->name('pemilik.store');
    Route::get('/{id}/edit', [UnitController::class, 'edit'])->name('edit_Unit');
    Route::put('/{id}', [UnitController::class, 'update'])->name('Unit_update');
    Route::delete('/{id}', [UnitController::class, 'destroy'])->name('delete_Unit');


});
    route::resource('log-operasional', \App\Http\Controllers\LogOperasionalController::class);
    // AJAX Routes
Route::get('/log-operasional/{id}/description', [\App\Http\Controllers\LogOperasionalController::class, 'getDescription'])->name('get_LogOperasionalDescription');

// Export Routes
Route::get('/log-operasional/export/excel', [\App\Http\Controllers\LogOperasionalController::class, 'exportExcel'])->name('export_LogOperasional');
Route::get('/log-operasional/export/pdf', [\App\Http\Controllers\LogOperasionalController::class, 'exportPDF'])->name('export_LogOperasionalPDF');
    // route::prefix('sparepart')->group(function () {
    //     Route::get('/permintaan', [SparepartController::class, 'permintaan'])->name('permintaan.index');
    //     Route::post('/permintaan/store', [SparepartController::class, 'storePermintaan'])->name('permintaan.store');
    //     // Route::get('/permintaan/{id}/edit', [SparepartController::class, 'editPermintaan'])->name('permintaan.edit');
    //     // Route::put('/permintaan/{id}', [SparepartController::class, 'updatePermintaan'])->name('permintaan.update');
    //     // Route::delete('/permintaan/{id}', [SparepartController::class, 'destroyPermintaan'])->name('permintaan.destroy');
    // });



// Atau bisa menggunakan resource route dengan except create dan edit
// karena kita menggunakan modal untuk form
Route::resource('maintenance-schedule', MaintenanceScheduleController::class)->except(['create', 'edit']);
Route::patch('maintenance-schedule/{id}/status', [MaintenanceScheduleController::class, 'updateStatus'])->name('maintenance-schedule.update-status');
});


