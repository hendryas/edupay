@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Siswa')

@section('content')

    <div class="page-heading">
        <h3>Formulir Pendaftaran Sekolah</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form id="formPendaftaran" action="{{ route('pendaftaran.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- =================== DATA SISWA =================== --}}
                    <h5 class="mt-4">👦 Data Siswa</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="siswa_nama" class="form-control">
                            <div class="invalid-feedback" id="error-siswa_nama"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>NISN <span class="text-danger">*</span></label>
                            <input type="text" name="siswa_nisn" class="form-control">
                            <div class="invalid-feedback" id="error-siswa_nisn"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" name="siswa_tempat_lahir" class="form-control">
                            <div class="invalid-feedback" id="error-siswa_tempat_lahir"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" name="siswa_tanggal_lahir" class="form-control">
                            <div class="invalid-feedback" id="error-siswa_tanggal_lahir"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="siswa_jenis_kelamin" class="form-control">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <div class="invalid-feedback" id="error-siswa_jenis_kelamin"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jurusan <span class="text-danger">*</span></label>
                            <select name="siswa_jurusan" class="form-control">
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="TJKT">Teknik Jaringan Komputer dan Telekomunikasi</option>
                                <option value="DKV">Desain Komunikasi Visual</option>
                                <option value="AKL">Akuntansi Keuangan Lembaga</option>
                                <option value="PM">Pemasaran</option>
                                <option value="MPLB">Manajemen Perkantoran dan Layanan Bisnis</option>
                            </select>
                            <div class="invalid-feedback" id="error-siswa_jurusan"></div>
                        </div>
                    </div>

                    {{-- =================== DATA WALI =================== --}}
                    <h5 class="mt-3">🧑‍👩‍👦 Data Wali</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nama Wali <span class="text-danger">*</span></label>
                            <input type="text" name="wali_nama" class="form-control">
                            <div class="invalid-feedback" id="error-wali_nama"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>No HP <span class="text-danger">*</span></label>
                            <input type="text" name="wali_hp" class="form-control">
                            <div class="invalid-feedback" id="error-wali_hp"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Jenis Kelamin <span class="text-danger">*</span></label>
                            <select name="wali_jenis_kelamin" class="form-control">
                                <option value="">-- Pilih --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <div class="invalid-feedback" id="error-wali_jenis_kelamin"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Pekerjaan <span class="text-danger">*</span></label>
                            <select name="wali_pekerjaan" id="wali_pekerjaan" class="form-control">
                                <option value="">-- Pilih Pekerjaan Orang Tua --</option>
                                @foreach ($pekerjaan_ortu as $pekerjaan)
                                    <option value="{{ $pekerjaan->nama_pekerjaan }}">{{ $pekerjaan->nama_pekerjaan }}
                                    </option>
                                @endforeach
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <div class="invalid-feedback" id="error-wali_pekerjaan"></div>
                        </div>
                        <div class="col-md-6 mb-3 d-none" id="pekerjaan_lain_div">
                            <label>Pekerjaan Orang Tua (Lainnya)</label>
                            <input type="text" name="wali_pekerjaan_lainnya" class="form-control"
                                placeholder="Tulis pekerjaan lainnya">
                            <div class="invalid-feedback" id="error-wali_pekerjaan_lainnya"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Hubungan dengan Siswa <span class="text-danger">*</span></label>
                            <select name="hubungan_dengan_siswa" class="form-control">
                                <option value="">-- Pilih Hubungan --</option>
                                <option value="Ayah">Ayah</option>
                                <option value="Ibu">Ibu</option>
                                <option value="Wali">Wali</option>
                            </select>
                            <div class="invalid-feedback" id="error-hubungan_dengan_siswa"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Alamat <span class="text-danger">*</span></label>
                            <textarea name="wali_alamat" class="form-control"></textarea>
                            <div class="invalid-feedback" id="error-wali_alamat"></div>
                        </div>
                    </div>

                    {{-- =================== UPLOAD DOKUMEN =================== --}}
                    <h5 class="mt-4">📎 Upload Dokumen</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Foto Siswa (JPG/PNG) <span class="text-danger">*</span></label>
                            <input type="file" name="foto_siswa" class="form-control" accept="image/*">
                            <div class="invalid-feedback" id="error-foto_siswa"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Akta Kelahiran (PDF) <span class="text-danger">*</span></label>
                            <input type="file" name="akta_kelahiran" class="form-control" accept="application/pdf">
                            <div class="invalid-feedback" id="error-akta_kelahiran"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Kartu Keluarga (PDF) <span class="text-danger">*</span></label>
                            <input type="file" name="kartu_keluarga" class="form-control" accept="application/pdf">
                            <div class="invalid-feedback" id="error-kartu_keluarga"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Ijazah Terakhir (PDF) <span class="text-danger">*</span></label>
                            <input type="file" name="ijazah_terakhir" class="form-control" accept="application/pdf">
                            <div class="invalid-feedback" id="error-ijazah_terakhir"></div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary">Kirim Pendaftaran</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- =============== SweetAlert & AJAX =============== --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#formPendaftaran').on('submit', function(e) {
                e.preventDefault();

                $('.invalid-feedback').text('');
                $('.form-control').removeClass('is-invalid');

                let formData = new FormData(this);

                Swal.fire({
                    title: 'Mohon tunggu',
                    html: 'Sedang memproses pendaftaran siswa...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            html: `<strong>${res.message}</strong><br>
                               Tagihan: <b>${res.tagihan.nama}</b><br>
                               Jumlah: Rp ${res.tagihan.jumlah}`,
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.close();

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('[name="' + key + '"]').addClass('is-invalid');
                                $('#error-' + key).text(value[0]);
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                text: 'Harap periksa kembali inputan Anda.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan pada server.'
                            });
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $('#wali_pekerjaan').on('change', function() {
            if ($(this).val() === 'Lainnya') {
                $('#pekerjaan_lain_div').removeClass('d-none');
            } else {
                $('#pekerjaan_lain_div').addClass('d-none');
                $('input[name="wali_pekerjaan_lainnya"]').val('');
            }
        });
    </script>
@endsection
