@extends('layouts.app')

@section('title', 'Pendaftaran Ditolak')

@section('content')
    <div class="page-heading">
        <h3>Informasi Pendaftaran</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="alert alert-warning">
                    <h5 class="text-warning">⚠️ Anda sudah melakukan pendaftaran sekolah.</h5>
                    <p>Silakan tunggu proses verifikasi atau hubungi pihak sekolah jika ada kesalahan.</p>
                    <a href="{{ route('tagihan.index') }}" class="btn btn-primary mt-3">Lihat Tagihan</a>
                </div>
            </div>
        </div>
    </section>
@endsection
