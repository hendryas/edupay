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
                            <th>Tanggal</th>
                            <th>Nominal</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Bukti Transfer</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaksiList as $i => $trx)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $trx->siswa->nama ?? '-' }}</td>
                                <td>{{ $trx->tagihan->nama_tagihan ?? '-' }}</td>
                                <td>{{ $trx->tanggal_bayar }}</td>
                                <td>Rp{{ number_format($trx->jumlah_bayar, 0, ',', '.') }}</td>
                                <td>{{ strtoupper($trx->metode) }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $trx->status == 'lunas' ? 'success' : ($trx->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($trx->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($trx->bukti_transfer)
                                        @if (Str::endsWith($trx->bukti_transfer, ['.pdf']))
                                            <a href="{{ asset('storage/' . $trx->bukti_transfer) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">Lihat PDF</a>
                                        @else
                                            <img src="{{ asset('storage/' . $trx->bukti_transfer) }}" alt="Bukti"
                                                width="100">
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($trx->status == 'pending')
                                        <form action="{{ route('admin.transaksi.verifikasi', $trx->id) }}" method="POST">
                                            @csrf
                                            <select name="status" class="form-select mb-2" required>
                                                <option value="">Pilih Status</option>
                                                <option value="lunas">Lunas</option>
                                                <option value="gagal">Gagal</option>
                                            </select>
                                            <button type="submit" class="btn btn-success btn-sm">Verifikasi</button>
                                        </form>
                                    @else
                                        <span class="text-muted">Sudah Diverifikasi</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
