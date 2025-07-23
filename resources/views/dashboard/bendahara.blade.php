@extends('layouts.app')

@section('title', 'Dashboard Bendahara')

@section('content')
    <div class="page-heading">
        <h3>Dashboard Bendahara</h3>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-light-info">
                    <div class="card-body">
                        <h6>Total Tagihan</h6>
                        <h3>{{ $totalTagihanAktif }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-light-success">
                    <div class="card-body">
                        <h6>Pembayaran Lunas</h6>
                        <h3>Rp {{ number_format($totalPembayaran) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-light-warning">
                    <div class="card-body">
                        <h6>Tagihan Pending</h6>
                        <h3>{{ $pendingVerifikasi }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
