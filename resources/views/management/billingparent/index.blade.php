{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading">
        <h3>Management Billing Parent</h3>
    </div>

    {{-- Tambahkan komponen dashboard kamu di sini --}}
    <div class="page-heading">
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        Data Management Billing Parent
                    </h5>
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah
                        +</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Tagihan</th>
                                <th>Nominal</th>
                                <th>Nama Siswa</th>
                                <th>Nama Orang Tua</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataTagihan as $index => $tagihan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tagihan->tagihan_siswa }}</td>
                                    <td>Rp{{ number_format($tagihan->nominal_tagihan, 0, ',', '.') }}</td>
                                    <td>{{ $tagihan->nama_siswa }}</td>
                                    <td>{{ $tagihan->nama_orang_tua }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </section>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formTambah" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Tagihan Orang Tua</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="orang_tua_id" class="form-label">Pilih Orang Tua</label>
                            <select name="orang_tua_id[]" id="orang_tua_id" class="form-select" multiple required>
                                @foreach ($orangTua as $ortu)
                                    <option value="{{ $ortu->id }}">{{ $ortu->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Gunakan Ctrl / Cmd untuk memilih lebih dari satu</small>
                            <div class="invalid-feedback" id="error-orang_tua_id"></div>
                        </div>

                        <div class="mb-3">
                            <label for="billing_type_id" class="form-label">Pilih Jenis Tagihan</label>
                            <select name="billing_type_id" id="billing_type_id" class="form-select" required>
                                <option value="">-- Pilih Tagihan --</option>
                                @foreach ($billingTypes as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name }} - Rp{{ number_format($type->amount, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="error-billing_type_id"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $('#formTambah').on('submit', function(e) {
            e.preventDefault(); // Mencegah reload

            // Bersihkan error sebelumnya
            $('.invalid-feedback').text('');
            $('select').removeClass('is-invalid');

            let formData = $(this).serialize();

            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('billingparent.store') }}",
                type: "POST",
                data: formData,
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Tagihan berhasil ditambahkan.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#modalTambah').modal('hide');
                        $('#formTambah')[0].reset();
                        window.location.reload(); // Refresh agar data terbaru muncul
                    });
                },
                error: function(xhr) {
                    Swal.close();

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            let input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
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
                            text: 'Terjadi kesalahan. Silakan coba lagi.'
                        });
                    }
                }
            });
        });
    </script>

@endsection
