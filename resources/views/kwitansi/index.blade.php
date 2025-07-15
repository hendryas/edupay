@extends('layouts.app')

@section('title', 'Daftar Kwitansi Pembayaran')

@section('content')
    <div class="page-heading">
        <h3>Daftar Kwitansi Pembayaran</h3>
        <p class="text-subtitle text-muted">Berikut adalah daftar pembayaran yang telah dilakukan oleh siswa.</p>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Tagihan</th>
                            <th>Periode</th>
                            <th>Jumlah Bayar</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatList as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->nama_siswa }}</td>
                                <td>{{ $item->nama_tagihan }}</td>
                                <td>{{ $item->periode }}</td>
                                <td>Rp{{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                                <td><span class="badge bg-info">{{ strtoupper($item->metode) }}</span></td>
                                <td>
                                    <span
                                        class="badge bg-{{ $item->status === 'lunas' ? 'success' : ($item->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('kwitansi.show', $item->id) }}" class="btn btn-primary btn-sm">
                                        Lihat
                                    </a>
                                    <a href="{{ route('kwitansi.cetak', $item->id) }}" class="btn btn-success btn-sm"
                                        target="_blank">
                                        Cetak PDF
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Belum ada transaksi pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
