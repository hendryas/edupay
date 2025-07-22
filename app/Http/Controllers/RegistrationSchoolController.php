<?php

namespace App\Http\Controllers;

use App\Models\BillingType;
use App\Models\OrangTua;
use App\Models\PekerjaanOrtu;
use App\Models\RegistrationSchool;
use App\Models\Siswa;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class RegistrationSchoolController extends Controller
{
    public function index()
    {
        $user_id = session('user_id'); // atau Auth::id() jika menggunakan auth default

        $sudahDaftar = RegistrationSchool::where('user_id', $user_id)->exists();

        if ($sudahDaftar) {
            return view('registrationschool.sudah_daftar');
        }

        $pekerjaan_ortu = PekerjaanOrtu::all();

        return view('registrationschool.index', compact('pekerjaan_ortu'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $user_id = session('user_id');

        $validated = $request->validate([
            // Data Wali
            'wali_nama' => 'required|string|max:255',
            'wali_hp' => 'required|string|max:20',
            'wali_alamat' => 'required|string',
            'wali_jenis_kelamin' => 'required|string',
            'wali_pekerjaan' => 'required|string',
            'hubungan_dengan_siswa' => 'required|string',
            'wali_pekerjaan_lainnya' => $request->wali_pekerjaan === 'Lainnya' ? 'required|string|max:100' : 'nullable',

            // Data Siswa
            'siswa_nama' => 'required|string|max:255',
            'siswa_nisn' => 'nullable|string|max:20',
            'siswa_tempat_lahir' => 'nullable|string|max:100',
            'siswa_tanggal_lahir' => 'nullable|date',
            'siswa_jenis_kelamin' => 'required|in:L,P',
            'siswa_jurusan' => 'required|in:TJKT,DKV,AKL,PM,MPLB',

            // Dokumen Upload
            'foto_siswa' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'akta_kelahiran' => 'required|mimes:pdf|max:2048',
            'kartu_keluarga' => 'required|mimes:pdf|max:2048',
            'ijazah_terakhir' => 'nullable|mimes:pdf|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Pastikan folder ada
            $fotoPath = 'pendaftaran/foto';
            $aktaPath = 'pendaftaran/akta';
            $kkPath   = 'pendaftaran/kk';
            $ijazahPath = 'pendaftaran/ijazah';

            Storage::disk('public')->makeDirectory($fotoPath);
            Storage::disk('public')->makeDirectory($aktaPath);
            Storage::disk('public')->makeDirectory($kkPath);
            Storage::disk('public')->makeDirectory($ijazahPath);

            // Upload file
            $foto_siswa = $request->file('foto_siswa')->store($fotoPath, 'public');
            $akta_kelahiran = $request->file('akta_kelahiran')->store($aktaPath, 'public');
            $kartu_keluarga = $request->file('kartu_keluarga')->store($kkPath, 'public');
            $ijazah_terakhir = $request->hasFile('ijazah_terakhir')
                ? $request->file('ijazah_terakhir')->store($ijazahPath, 'public')
                : null;

            if ($request->wali_pekerjaan === 'Lainnya' && $request->wali_pekerjaan_lainnya) {
                \App\Models\PekerjaanOrtu::firstOrCreate([
                    'nama_pekerjaan' => $request->wali_pekerjaan_lainnya
                ], [
                    'kode_pekerjaan' => strtoupper(substr($request->wali_pekerjaan_lainnya, 0, 3)) . rand(100, 999)
                ]);
            }

            // Ambil pekerjaan dari dropdown atau input manual jika "Lainnya"
            $pekerjaanOrtu = $request->wali_pekerjaan === 'Lainnya'
                ? $request->wali_pekerjaan_lainnya
                : $request->wali_pekerjaan;

            // Buat data orang tua
            $orangTua = OrangTua::create([
                'user_id' => $user_id,
                'nama_lengkap' => $validated['wali_nama'],
                'no_hp' => $validated['wali_hp'],
                'alamat' => $validated['wali_alamat'],
                'jenis_kelamin' => $validated['wali_jenis_kelamin'],
                'pekerjaan' => $pekerjaanOrtu,
                'hubungan_dengan_siswa' => $validated['hubungan_dengan_siswa'],
            ]);

            // Simpan data ke database
            $pendaftaran = RegistrationSchool::create([
                'user_id' => $user_id,
                'orang_tua_id' => $orangTua->id,
                'wali_nama' => $validated['wali_nama'],
                'wali_hp' => $validated['wali_hp'],
                'wali_alamat' => $validated['wali_alamat'],
                'siswa_nama' => $validated['siswa_nama'],
                'siswa_nisn' => $validated['siswa_nisn'],
                'siswa_tempat_lahir' => $validated['siswa_tempat_lahir'],
                'siswa_tanggal_lahir' => $validated['siswa_tanggal_lahir'],
                'siswa_jenis_kelamin' => $validated['siswa_jenis_kelamin'],
                'siswa_jurusan' => $validated['siswa_jurusan'],
                'foto_siswa' => $foto_siswa,
                'akta_kelahiran' => $akta_kelahiran,
                'kartu_keluarga' => $kartu_keluarga,
                'ijazah_terakhir' => $ijazah_terakhir,
                'tanggal_pendaftaran' => now(),
                'status_pendaftaran' => 'menunggu_verifikasi',
            ]);

            // Buat data siswa
            $siswa = Siswa::create([
                'orang_tua_id' => $orangTua->id,
                'nis' => 'REG-' . time(), // Auto-generated sementara
                'nama' => $validated['siswa_nama'],
                'kelas' => 'Calon Siswa',
                'nomor_wa' => $validated['wali_hp'],
            ]);

            // Update registration untuk menyimpan id orang tua
            $pendaftaran->update([
                'orang_tua_id' => $orangTua->id,
            ]);

            $orangTua->update([
                'siswa_id' => $siswa->id
            ]);

            // Ambil atau buat Billing Type
            $billingType = BillingType::firstOrCreate(
                ['code' => 'DAFTAR'],
                ['name' => 'Biaya Pendaftaran', 'amount' => 250000]
            );

            // Buat Tagihan Otomatis
            $tagihan = Tagihan::create([
                'siswa_id' => $siswa->id,
                'biling_type_id' => $billingType->id,
                'nama_tagihan' => 'Biaya Pendaftaran Siswa Baru',
                'nominal' => $billingType->amount,
                'periode' => now()->format('F Y'),
                'deskripsi' => 'Tagihan otomatis setelah pendaftaran',
                'status_pembayaran' => 'pending',
            ]);

            DB::commit();

            if ($orangTua->no_hp) {
                $pesan = "Halo {$orangTua->nama_lengkap},\n\n" .
                    "Kami informasikan bahwa *tagihan pendaftaran sekolah* untuk ananda *{$siswa->nama}* dengan rincian *{$tagihan->nama_tagihan}* sebesar *Rp" . number_format($tagihan->nominal, 0, ',', '.') . "* masih *belum dibayar*.\n\n" .
                    "Segera lakukan pembayaran agar proses pendaftaran dapat dilanjutkan.\n\n" .
                    "Terima kasih atas perhatian dan kerja samanya 🙏\n\n" .
                    "*SMK Tunas Harapan*";
                \App\Services\FonnteService::send($orangTua->no_hp, $pesan);
            }


            return response()->json([
                'message' => 'Pendaftaran, data siswa, dan tagihan berhasil disimpan.',
                'siswa_id' => $siswa->id,
                'tagihan' => [
                    'nama' => 'Biaya Pendaftaran Siswa Baru',
                    'jumlah' => number_format($billingType->amount, 0, ',', '.'),
                    'status' => 'pending',
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menyimpan pendaftaran: ' . $e->getMessage());

            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
