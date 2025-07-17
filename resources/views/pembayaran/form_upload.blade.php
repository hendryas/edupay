@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
    <div class="page-heading">
        <h3>Upload Bukti Pembayaran</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="mb-4">
                    <h5>Detail Tagihan</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Nama Siswa:</strong> {{ $tagihan->nama_siswa }}</li>
                        <li class="list-group-item"><strong>Nama Tagihan:</strong> {{ $tagihan->nama_tagihan }}</li>
                        <li class="list-group-item"><strong>Nominal:</strong>
                            Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}</li>
                        <li class="list-group-item">
                            <strong>Status:</strong>
                            <span
                                class="badge bg-{{ $tagihan->status_pembayaran == 'lunas' ? 'success' : ($tagihan->status_pembayaran == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($tagihan->status_pembayaran) }}
                            </span>
                        </li>
                        <li class="list-group-item"><strong>No. Rekening:</strong> 1928782562 (BCA)</li>
                    </ul>
                </div>

                @if ($tagihan->status_pembayaran != 'lunas')
                    <form id="formUploadBukti" action="{{ route('tagihan.upload.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tagihan_id" value="{{ $tagihan->id }}">

                        <div class="mb-3">
                            <label for="bukti_transfer" class="form-label">Upload Bukti Transfer</label>
                            <input type="file" name="bukti_transfer" id="bukti_transfer" class="form-control"
                                accept=".jpg,.jpeg,.png,.pdf">
                            <div class="invalid-feedback" id="error-bukti_transfer"></div>
                        </div>

                        <button type="submit" class="btn btn-primary">Upload Bukti</button>
                    </form>
                @else
                    <div class="alert alert-info">Tagihan ini sudah lunas. Tidak perlu mengunggah bukti lagi.</div>
                    <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Kembali ke Daftar Tagihan</a>
                @endif

            </div>
        </div>
    </section>

    {{-- =============== SweetAlert & AJAX =============== --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#formUploadBukti').on('submit', function(e) {
                e.preventDefault();

                $('.invalid-feedback').text('');
                $('.form-control').removeClass('is-invalid');

                let formData = new FormData(this);

                Swal.fire({
                    title: 'Mengirim...',
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
                            text: res.message ?? 'Bukti transfer berhasil diunggah.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ route('tagihan.index') }}";
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


@endsection
