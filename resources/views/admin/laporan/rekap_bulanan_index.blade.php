@extends('layouts.app')

@section('title', 'Rekap Transaksi Bulanan')

@section('content')
    <div class="page-heading">
        <h3>Rekap Transaksi Bulanan</h3>
        <p class="text-subtitle text-muted">Pilih bulan dan tahun untuk mengekspor laporan transaksi ke Excel.</p>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('laporan.rekap.export') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select name="bulan" id="bulan" class="form-select" required>
                            <option value="">-- Pilih Bulan --</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tahun" class="form-label">Tahun</label>
                        <select name="tahun" id="tahun" class="form-select" required>
                            <option value="">-- Pilih Tahun --</option>
                            @for ($y = date('Y'); $y >= 2020; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Export Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
