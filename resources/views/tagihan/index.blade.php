@extends('layouts.app')

@section('title', 'Daftar Tagihan Siswa')

@section('content')
    <div class="page-heading">
        <h3>Daftar Tagihan Siswa</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                @if (isset($message))
                    <div class="alert alert-warning">{{ $message }}</div>
                @elseif ($tagihanList->count() > 0)
                    <h5 class="mb-3">Nama Siswa: <strong>{{ $siswa->nama }}</strong></h5>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Tagihan</th>
                                <th>Periode</th>
                                <th>Nominal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tagihanList as $index => $tagihan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tagihan->nama_tagihan }}</td>
                                    <td>{{ $tagihan->periode }}</td>
                                    <td>Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($tagihan->status_pembayaran === 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @elseif($tagihan->status_pembayaran === 'menunggu_verifikasi' || $tagihan->status_pembayaran === 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu
                                                Verifikasi</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($tagihan->status_pembayaran !== 'lunas')
                                            <!-- Tombol VA -->

                                            @if (
                                                $tagihan->status_pembayaran === 'lunas' ||
                                                    $tagihan->status_pembayaran === 'menunggu_verifikasi' ||
                                                    $tagihan->status_pembayaran === 'pending')
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modalVA{{ $tagihan->id }}" disabled>
                                                    Bayar VA
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modalVA{{ $tagihan->id }}">
                                                    Bayar VA
                                                </button>
                                            @endif

                                            <!-- Tombol Transfer Manual -->
                                            @if (
                                                $tagihan->status_pembayaran === 'lunas' ||
                                                    $tagihan->status_pembayaran === 'menunggu_verifikasi' ||
                                                    $tagihan->status_pembayaran === 'pending')
                                                <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                    data-bs-target="#modalTransfer{{ $tagihan->id }}" disabled>
                                                    Bayar Transfer Manual
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                    data-bs-target="#modalTransfer{{ $tagihan->id }}">
                                                    Bayar Transfer Manual
                                                </button>
                                            @endif

                                            <!-- Tombol Transfer Manual -->
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                data-bs-target="#modalLihat{{ $tagihan->id }}">
                                                Lihat Bukti Transaksi
                                            </button>

                                            <!-- Modal VA -->
                                            <div class="modal fade" id="modalVA{{ $tagihan->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <form id="formBayarVA{{ $tagihan->id }}" class="modal-content"
                                                        method="POST" action="{{ route('tagihan.bayar') }}">
                                                        @csrf
                                                        <input type="hidden" name="tagihan_id"
                                                            value="{{ $tagihan->id }}">
                                                        <input type="hidden" name="metode" value="va">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Pembayaran via Virtual Account</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <p><strong>Nama Tagihan:</strong> {{ $tagihan->nama_tagihan }}
                                                            </p>
                                                            <p><strong>Periode:</strong> {{ $tagihan->periode }}</p>
                                                            <p><strong>Nominal:</strong>
                                                                Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}</p>
                                                            <hr>
                                                            <p>Simulasi pembayaran akan diproses melalui Virtual Account.
                                                            </p>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success">Bayar
                                                                Sekarang</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Modal Transfer Manual -->
                                            <div class="modal fade" id="modalTransfer{{ $tagihan->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <form id="formBayarTransfer{{ $tagihan->id }}" class="modal-content"
                                                        method="POST" action="{{ route('tagihan.bayar') }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="tagihan_id"
                                                            value="{{ $tagihan->id }}">
                                                        <input type="hidden" name="metode" value="transfer">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Pembayaran via Transfer Manual</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <p><strong>Nama Tagihan:</strong> {{ $tagihan->nama_tagihan }}
                                                            </p>
                                                            <p><strong>Periode:</strong> {{ $tagihan->periode }}</p>
                                                            <p><strong>Nominal:</strong>
                                                                Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}</p>

                                                            <div class="alert alert-info mt-3">
                                                                Silakan transfer ke rekening berikut:<br>
                                                                <strong>Bank BCA</strong><br>
                                                                No Rek: 1234567890<br>
                                                                a.n. SMK Tunas Harapan
                                                            </div>

                                                            <div class="mt-3">
                                                                <label for="bukti_transfer{{ $tagihan->id }}">Upload Bukti
                                                                    Transfer (JPG/PDF)</label>
                                                                <input type="file" name="bukti_transfer"
                                                                    id="bukti_transfer{{ $tagihan->id }}"
                                                                    class="form-control" accept="image/*,application/pdf"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success">Kirim Bukti &
                                                                Bayar</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <!-- Modal Lihat Bukti Transaksi -->
                                            <div class="modal fade" id="modalLihat{{ $tagihan->id }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Detail Bukti Transaksi</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>Nama Tagihan:</strong> {{ $tagihan->nama_tagihan }}
                                                            </p>
                                                            <p><strong>Periode:</strong> {{ $tagihan->periode }}</p>
                                                            <p><strong>Nominal:</strong>
                                                                Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}</p>
                                                            <p><strong>Metode Pembayaran:</strong>
                                                                @if ($tagihan->metode_pembayaran === 'va')
                                                                    Virtual Account
                                                                @elseif ($tagihan->metode_pembayaran === 'transfer')
                                                                    Transfer Manual
                                                                @else
                                                                    -
                                                                @endif
                                                            </p>
                                                            <p><strong>Status Pembayaran:</strong>
                                                                @if ($tagihan->status_pembayaran === 'lunas')
                                                                    <span class="badge bg-success">Lunas</span>
                                                                @elseif($tagihan->status_pembayaran === 'menunggu_verifikasi' || $tagihan->status_pembayaran === 'pending')
                                                                    <span class="badge bg-warning text-dark">Menunggu
                                                                        Verifikasi</span>
                                                                @else
                                                                    <span class="badge bg-secondary">Belum Bayar</span>
                                                                @endif
                                                            </p>

                                                            @if ($tagihan->metode_pembayaran === 'transfer' && $tagihan->bukti_transfer)
                                                                <hr>
                                                                <p><strong>Bukti Transfer:</strong></p>
                                                                @if (Str::endsWith($tagihan->bukti_transfer, ['.jpg', '.jpeg', '.png']))
                                                                    <img src="{{ asset('storage/' . $tagihan->bukti_transfer) }}"
                                                                        alt="Bukti Transfer"
                                                                        class="img-fluid rounded border">
                                                                @elseif(Str::endsWith($tagihan->bukti_transfer, ['.pdf']))
                                                                    <a href="{{ asset('storage/' . $tagihan->bukti_transfer) }}"
                                                                        target="_blank"
                                                                        class="btn btn-outline-primary btn-sm">Lihat File
                                                                        PDF</a>
                                                                @endif
                                                            @elseif($tagihan->metode_pembayaran === 'va')
                                                                <div class="alert alert-info">Transaksi dilakukan via
                                                                    Virtual Account.</div>
                                                            @else
                                                                <div class="alert alert-warning">Belum ada bukti transaksi
                                                                    tersedia.</div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info">Tidak ada tagihan tersedia.</div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Tangkap semua form dengan prefix id formBayarVA atau formBayarTransfer
            $('form[id^="formBayar"]').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);

                Swal.fire({
                    title: 'Proses Pembayaran?',
                    text: "Pastikan data sudah benar!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Bayar!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: $(form).attr('action'),
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(res) {
                                Swal.fire({
                                    icon: res.status === 'success' ? 'success' :
                                        'info',
                                    title: res.status === 'success' ?
                                        'Berhasil!' : 'Info',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                if (res.status === 'success') {
                                    setTimeout(() => {
                                        window.location.reload();
                                    }, 2000);
                                }
                            },
                            error: function(xhr) {
                                let msg = xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat memproses pembayaran.';
                                Swal.fire('Error', msg, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
