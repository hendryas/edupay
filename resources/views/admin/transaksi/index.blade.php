@extends('layouts.app')

@section('title', 'Verifikasi Transaksi Pembayaran')

@section('content')
    <div class="page-heading">
        <h3>Verifikasi Transaksi Pembayaran</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body table-responsive">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Tagihan</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tagihanList as $i => $trx)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $trx->nama_siswa ?? '-' }}</td>
                                <td>{{ $trx->nama_tagihan ?? '-' }}</td>
                                <td>Rp{{ number_format($trx->nominal, 0, ',', '.') }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $trx->status_pembayaran == 'lunas' ? 'success' : ($trx->status_pembayaran == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($trx->status_pembayaran) }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalTagihan{{ $trx->id }}">
                                        Cek Tagihan
                                    </button>

                                    @if ($trx->has_transaksi)
                                        <a href="{{ route('tagihan.history', $trx->id) }}"
                                            class="btn btn-warning btn-sm mt-1">
                                            Cek History Pembayaran
                                        </a>
                                    @endif

                                    <!-- Modal -->
                                    <div class="modal fade" id="modalTagihan{{ $trx->id }}" tabindex="-1"
                                        aria-labelledby="modalTagihanLabel{{ $trx->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary text-white">
                                                    <h5 class="modal-title" id="modalTagihanLabel{{ $trx->id }}">
                                                        Detail Tagihan</h5>
                                                    <button type="button" class="btn-close text-white"
                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Nama Siswa:</strong> {{ $trx->nama_siswa }}</p>
                                                    <p><strong>Nama Tagihan:</strong> {{ $trx->nama_tagihan }}</p>
                                                    <p><strong>Nominal:</strong>
                                                        Rp{{ number_format($trx->nominal, 0, ',', '.') }}</p>
                                                    <p><strong>Status:</strong>
                                                        <span
                                                            class="badge bg-{{ $trx->status_pembayaran == 'lunas' ? 'success' : ($trx->status_pembayaran == 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($trx->status_pembayaran) }}
                                                        </span>
                                                    </p>

                                                    @if ($trx->status_pembayaran == 'pending')
                                                        <div class="d-grid gap-2">
                                                            {{-- Redirect ke halaman form pembayaran VA --}}
                                                            <a href="{{ route('tagihan.va.form', $trx->id) }}"
                                                                class="btn btn-success btn-sm">
                                                                Bayar Sekarang via Virtual Account
                                                            </a>

                                                            {{-- Redirect ke halaman form upload bukti transfer --}}
                                                            <a href="{{ route('tagihan.upload.form', $trx->id) }}"
                                                                class="btn btn-outline-primary btn-sm">
                                                                Upload Bukti Transfer Manual
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="alert alert-success mt-3">Pembayaran sudah diverifikasi.
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
