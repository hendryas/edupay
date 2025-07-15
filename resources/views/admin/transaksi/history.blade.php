@extends('layouts.app')

@section('title', 'Riwayat Pembayaran Tagihan')

<style>
    .bukti-preview {
        cursor: pointer;
        transition: 0.3s;
    }

    .bukti-preview:hover {
        transform: scale(1.05);
    }
</style>

<script>
    function openPreview(src) {
        Swal.fire({
            title: 'Preview Bukti Transfer',
            html: `<img src="${src}" style="max-width:100%; height:auto;">`,
            showCloseButton: true,
            showConfirmButton: false,
        });
    }
</script>


@section('content')
    <div class="page-heading">
        <h3>Riwayat Pembayaran</h3>
        <p class="text-subtitle text-muted">Menampilkan riwayat pembayaran tagihan oleh orang tua.</p>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">Tabel History Pembayaran</div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Tagihan</th>
                            <th>Periode</th>
                            <th>Nominal Tagihan</th>
                            <th>Jumlah Bayar</th>
                            <th>Tanggal Bayar</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Bukti Transfer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($historyList as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->nama_siswa ?? '-' }}</td>
                                <td>{{ $item->nama_tagihan ?? '-' }}</td>
                                <td>{{ $item->periode ?? '-' }}</td>
                                <td>Rp{{ number_format($item->nominal_tagihan ?? 0, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($item->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ strtoupper($item->metode) }}
                                    </span>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-{{ $item->status == 'lunas' ? 'success' : ($item->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($item->metode === 'transfer' && $item->bukti_transfer)
                                        @if (Str::endsWith($item->bukti_transfer, ['.pdf']))
                                            <a href="{{ asset('storage/' . $item->bukti_transfer) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">Lihat PDF</a>
                                        @else
                                            <img src="{{ asset('storage/' . $item->bukti_transfer) }}" alt="Bukti Transfer"
                                                width="100" class="bukti-preview"
                                                onclick="openPreview('{{ asset('storage/' . $item->bukti_transfer) }}')">
                                        @endif
                                    @elseif ($item->metode === 'midtrans')
                                        <span class="text-muted">Pembayaran via Midtrans (otomatis)</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">Belum ada riwayat pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <a href="{{ route('tagihan.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Tagihan</a>
            </div>
        </div>
    </section>
@endsection
