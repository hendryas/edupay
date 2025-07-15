@extends('layouts.app')

@section('title', 'Data Tagihan Pendaftaran')

@section('content')
    <div class="page-heading">
        <h3>Data Tagihan Seluruh Pendaftaran</h3>
        <p class="text-subtitle text-muted">Berikut adalah daftar tagihan kategori pendaftaran beserta metode dan bukti
            transaksi.</p>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Nama Tagihan</th>
                            <th>Periode</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tagihanList as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->nama_siswa ?? '-' }}</td>
                                <td>{{ $item->nama_tagihan }}</td>
                                <td>{{ $item->periode ?? '-' }}</td>
                                <td>Rp{{ number_format($item->nominal, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-info">{{ strtoupper($item->metode ?? '-') }}</span>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $item->status_pembayaran == 'lunas' ? 'success' : ($item->status_pembayaran == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($item->status_pembayaran) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}</td>
                                <td>
                                    @if ($item->metode === 'transfer' && $item->bukti_transfer)
                                        {{-- Tombol lihat bukti --}}
                                        @if (Str::endsWith($item->bukti_transfer, ['.pdf']))
                                            <a href="{{ asset('storage/' . $item->bukti_transfer) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary mb-1">Lihat PDF</a>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-primary mb-1"
                                                onclick="previewBukti('{{ asset('storage/' . $item->bukti_transfer) }}')">
                                                Lihat Bukti
                                            </button>
                                        @endif

                                        {{-- Verifikasi jika status pending --}}
                                        @if ($item->status_pembayaran === 'pending')
                                            <form class="form-verifikasi" data-tagihan-id="{{ $item->id }}"
                                                data-orangtua-id="{{ $item->orang_tua_id }}">
                                                <select name="status" class="form-select form-select-sm mb-1 status-select"
                                                    required>
                                                    <option value="">Verifikasi Sebagai</option>
                                                    <option value="lunas">Lunas</option>
                                                    <option value="gagal">Gagal</option>
                                                </select>
                                                <button type="button"
                                                    class="btn btn-success btn-sm btn-verifikasi">Verifikasi</button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Belum ada tagihan pendaftaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- Preview modal SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function previewBukti(src) {
            Swal.fire({
                title: 'Preview Bukti Transfer',
                html: `<img src="${src}" class="img-fluid" />`,
                showCloseButton: true,
                showConfirmButton: false
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.btn-verifikasi').on('click', function() {
                const form = $(this).closest('.form-verifikasi');
                const tagihanId = form.data('tagihan-id');
                const status = form.find('.status-select').val();
                const orangTuaId = form.data('orangtua-id');

                if (!status) {
                    Swal.fire('Peringatan', 'Silakan pilih status terlebih dahulu.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Verifikasi',
                    text: `Yakin ingin memverifikasi tagihan ini sebagai "${status.toUpperCase()}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Verifikasi',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('tagihan.veriftagihan') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                tagihan_id: tagihanId,
                                orang_tua_id: orangTuaId,
                                status: status
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Memproses...',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(res) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Status tagihan berhasil diverifikasi.',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: xhr.responseJSON?.message ??
                                        'Terjadi kesalahan saat verifikasi.'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

@endsection
