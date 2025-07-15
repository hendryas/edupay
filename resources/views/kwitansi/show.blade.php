@extends('layouts.app')

@section('title', 'Kwitansi Pembayaran')

@section('content')
    <div class="page-heading">
        <h3>Kwitansi Pembayaran</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5>Nama Siswa: {{ $data->nama_siswa }}</h5>
                <p>Tagihan: {{ $data->nama_tagihan }}</p>
                <p>Periode: {{ $data->periode }}</p>
                <p>Jumlah Bayar: <strong>Rp{{ number_format($data->jumlah_bayar, 0, ',', '.') }}</strong></p>
                <p>Tanggal Bayar: {{ \Carbon\Carbon::parse($data->tanggal_bayar)->format('d M Y') }}</p>
                <p>Status: <span class="badge bg-success">{{ ucfirst($data->status) }}</span></p>

                <a href="{{ route('kwitansi.cetak', $data->id) }}" target="_blank" class="btn btn-primary mt-3">
                    Cetak Kwitansi PDF
                </a>
            </div>
        </div>
    </section>
@endsection
