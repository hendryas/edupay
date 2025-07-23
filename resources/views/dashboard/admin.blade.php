@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="page-heading">
        <h3>Dashboard Admin</h3>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-light-primary">
                    <div class="card-body">
                        <h6>Total Siswa</h6>
                        <h3>{{ $totalSiswa }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-light-info">
                    <div class="card-body">
                        <h6>Total Tagihan</h6>
                        <h3>{{ $totalTagihan }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-light-success">
                    <div class="card-body">
                        <h6>Total Pembayaran</h6>
                        <h3>Rp {{ number_format($totalPembayaran) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-light-warning">
                    <div class="card-body">
                        <h6>Tagihan Pending</h6>
                        <h3>{{ $verifikasiPending }}</h3>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
