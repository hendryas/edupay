@extends('layouts.app')

@section('title', 'Laporan Tagihan Pendaftaran')

@section('content')
    <div class="page-heading">
        <h3>Laporan Tagihan Pendaftaran</h3>
        <p class="text-subtitle text-muted">Berikut adalah laporan seluruh tagihan pendaftaran siswa.</p>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <a href="{{ route('laporan.tagihan.export') }}" class="btn btn-success mb-3">
                    <i class="bi bi-file-earmark-excel"></i> Export Excel
                </a>

                <button onclick="window.print()" class="btn btn-primary mb-3">
                    <i class="bi bi-printer"></i> Cetak
                </button>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Tagihan</th>
                                <th>Periode</th>
                                <th>Nominal</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Tgl Bayar</th>
                                <th>Bukti</th>
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
                                    <td><span class="badge bg-info">{{ strtoupper($item->metode ?? '-') }}</span></td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $item->status_pembayaran == 'lunas' ? 'success' : ($item->status_pembayaran == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($item->status_pembayaran) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $item->tanggal_bayar ? \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y') : '-' }}
                                    </td>
                                    <td>
                                        @if ($item->bukti_transfer)
                                            @if (Str::endsWith($item->bukti_transfer, ['.pdf']))
                                                <a href="{{ asset('storage/' . $item->bukti_transfer) }}"
                                                    target="_blank">PDF</a>
                                            @else
                                                <img src="{{ asset('storage/' . $item->bukti_transfer) }}" width="60">
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Tidak ada data.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
