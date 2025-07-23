@extends('layouts.app')

@section('title', 'Dashboard Orang Tua')

@section('content')
    <div class="page-heading">
        <h3>Dashboard Orang Tua</h3>
    </div>

    <div class="page-content">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h6 class="card-title text-primary">📢 Informasi Pembayaran</h6>
                <p class="card-text">
                    Pembayaran minimal dilakukan setiap tanggal <strong>10</strong> setiap bulannya untuk menghindari
                    keterlambatan.
                </p>
            </div>
        </div>


        <div class="row">
            <div class="col-md-4">
                <div class="card bg-light-info">
                    <div class="card-body">
                        <h6>Total Tagihan Anak</h6>
                        <h3>Rp {{ number_format($tagihanTotal) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light-success">
                    <div class="card-body">
                        <h6>Sudah Dibayar</h6>
                        <h3>Rp {{ number_format($sudahDibayar) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-light-warning">
                    <div class="card-body">
                        <h6>Status Terakhir</h6>
                        <h3>{{ $statusPembayaran }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Pembayaran --}}
        <div class="card mt-4">
            <div class="card-header">
                <h4>Riwayat Pembayaran Terbaru</h4>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    @forelse($riwayatPembayaran as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $item->nama_tagihan }}
                            <span class="badge bg-{{ $item->status_pembayaran == 'lunas' ? 'success' : 'warning' }}">
                                {{ ucfirst($item->status_pembayaran) }}
                            </span>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Belum ada pembayaran.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
