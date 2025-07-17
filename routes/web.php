<?php

use App\Http\Controllers\BillingTypeController;
use App\Http\Controllers\KwitansiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RegistrationSchoolController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::middleware('guest')->get('/', function () {
    return view('landing.landing');
})->name('landing');

Route::get('/home', function () {
    return view('landing.landing');
})->name('home');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware('auth')->group(function () {
    // Tampilkan form pendaftaran
    Route::get('/', [RegistrationSchoolController::class, 'index'])->name('pendaftaran.index');
    Route::post('/pendaftaran', [RegistrationSchoolController::class, 'store'])->name('pendaftaran.store');
    Route::get('/pendaftaran/{id}', [RegistrationSchoolController::class, 'show'])->name('pendaftaran.show');
    Route::get('/pendaftaran/{id}/edit', [RegistrationSchoolController::class, 'edit'])->name('pendaftaran.edit');
    Route::put('/pendaftaran/{id}', [RegistrationSchoolController::class, 'update'])->name('pendaftaran.update');
    Route::delete('/pendaftaran/{id}', [RegistrationSchoolController::class, 'destroy'])->name('pendaftaran.destroy');
});

Route::middleware('auth')->group(function () {
    Route::prefix('tagihan')->name('tagihan.')->group(function () {
        Route::get('/', [TagihanController::class, 'index'])->name('index');
        Route::post('/bayar', [TagihanController::class, 'bayar'])->name('bayar');
    });
});

Route::middleware('auth')->group(function () {
    Route::prefix('transaksi')->name('tagihan.')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('index');
        Route::post('/verifikasi', [TransaksiController::class, 'verifikasi'])->name('verifikasi');

        //Pembayaran dengan metode Upload Bukti Dari Orang Tua
        Route::get('/upload/{id}', [TransaksiController::class, 'formUpload'])->name('upload.form');
        Route::post('/upload', [TransaksiController::class, 'uploadBukti'])->name('upload.store');

        //Pembayaran dengan metode VA Dari Orang Tua
        Route::get('/va/{id}', [TransaksiController::class, 'formVA'])->name('va.form');
        Route::post('/va/process', [TransaksiController::class, 'processVA'])->name('va.process');

        Route::get('/transaksi/history/{tagihan_id}', [TransaksiController::class, 'history'])->name('history');

        // Admin
        Route::get('/tagihan/pendaftaran', [TagihanController::class, 'dataPendaftaran'])->name('pendaftaran');
        Route::post('/tagihan/verifikasi', [TagihanController::class, 'veriftagihan'])->name('veriftagihan');


    });
});

Route::middleware('auth')->group(function () {
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/tagihan-pendaftaran', [LaporanController::class, 'index'])->name('tagihan');
        Route::get('/tagihan-pendaftaran/export', [LaporanController::class, 'export'])->name('tagihan.export');
        Route::get('/rekap-transaksi', [LaporanController::class, 'rekapIndex'])->name('rekap.index');
        Route::get('/rekap-transaksi/export', [LaporanController::class, 'rekapExport'])->name('rekap.export');
    });
});

Route::middleware('auth')->group(function () {
    Route::prefix('riwayat')->name('riwayat.')->group(function () {
        Route::get('/pembayaran', [RiwayatController::class, 'index'])->name('pembayaran');
  });
});

Route::middleware(['auth'])->prefix('kwitansi')->name('kwitansi.')->group(function () {
    Route::get('/', [KwitansiController::class, 'index'])->name('index');
    Route::get('/{id}', [KwitansiController::class, 'show'])->name('show');
    Route::get('/{id}/cetak', [KwitansiController::class, 'cetak'])->name('cetak');
});

Route::middleware(['auth'])->prefix('billingtype')->name('billingtype.')->group(function () {
    Route::get('/', [BillingTypeController::class, 'index'])->name('index');
    Route::post('/add', [BillingTypeController::class, 'store'])->name('store');
    Route::put('/update/{id}', [BillingTypeController::class, 'update'])->name('update');
});


require __DIR__.'/auth.php';
