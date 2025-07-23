<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                return view('dashboard.admin', [
                    'totalSiswa' => Siswa::count(),
                    'totalTagihan' => Tagihan::count(),
                    'totalPembayaran' => Tagihan::where('status_pembayaran', 'lunas')->sum('nominal'),
                    'verifikasiPending' => Tagihan::where('status_pembayaran', 'pending')->count(),
                ]);

            case 'bendahara':
                return view('dashboard.bendahara', [
                    'totalTagihanAktif' => Tagihan::count(),
                    'totalPembayaran' => Tagihan::where('status_pembayaran', 'lunas')->sum('nominal'),
                    'pendingVerifikasi' => Tagihan::where('status_pembayaran', 'pending')->count(),
                ]);

            case 'orang_tua':
                $orangTua = OrangTua::where('user_id', $user->id)->first();
                $siswa = Siswa::where('id', $orangTua->siswa_id)->first();

                $tagihanSiswa = Tagihan::where('siswa_id', $siswa->id)->get();
                $tagihanTotal = $tagihanSiswa->sum('nominal');
                $sudahDibayar = $tagihanSiswa->where('status_pembayaran', 'lunas')->sum('nominal');
                $statusPembayaran = optional($tagihanSiswa->last())->status_pembayaran ?? 'Belum ada';

                return view('dashboard.ortu', [
                    'tagihanTotal' => $tagihanTotal,
                    'sudahDibayar' => $sudahDibayar,
                    'statusPembayaran' => ucfirst($statusPembayaran),
                    'riwayatPembayaran' => $tagihanSiswa->sortByDesc('created_at')->take(5),
                ]);

            default:
                abort(403);
        }
    }
}
