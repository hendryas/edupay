@extends('layouts.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
    <div class="page-heading">
        <h3>Upload Bukti Pembayaran</h3>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="mb-4">
                    <h5>Detail Tagihan</h5>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Nama Siswa:</strong> {{ $tagihan->nama_siswa }}</li>
                        <li class="list-group-item"><strong>Nama Tagihan:</strong> {{ $tagihan->nama_tagihan }}</li>
                        <li class="list-group-item"><strong>Nominal:</strong>
                            Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}</li>
                        <li class="list-group-item">
                            <strong>Status:</strong>
                            <span
                                class="badge bg-{{ $tagihan->status_pembayaran == 'lunas' ? 'success' : ($tagihan->status_pembayaran == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($tagihan->status_pembayaran) }}
                            </span>
                        </li>
                    </ul>
                </div>

                @if ($tagihan->status_pembayaran != 'lunas')
                    <form action="{{ route('tagihan.upload.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tagihan_id" value="{{ $tagihan->id }}">

                        <div class="mb-3">
                            <label for="bukti_transfer" class="form-label">Upload Bukti Transfer <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="bukti_transfer" id="bukti_transfer" class="form-control"
                                accept=".jpg,.jpeg,.png,.pdf" required>
                            <small class="text-muted">Format: JPG, PNG, atau PDF. Maks 2MB.</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Upload Bukti</button>
                        <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                @else
                    <div class="alert alert-info">Tagihan ini sudah lunas. Tidak perlu mengunggah bukti lagi.</div>
                    <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Kembali ke Daftar Tagihan</a>
                @endif

            </div>
        </div>
    </section>
@endsection
