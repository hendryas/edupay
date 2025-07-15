@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
    <div class="page-heading">
        <h3>Riwayat Pembayaran</h3>
        <p class="text-subtitle text-muted">Berikut adalah daftar pembayaran yang telah dilakukan.</p>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body table-responsive">
                @if (!empty($message))
                    <div class="alert alert-warning">{{ $message }}</div>
                @endif

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tagihan</th>
                            <th>Periode</th>
                            <th>Jumlah Bayar</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal Bayar</th>
                            <th>Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatList as $i => $item)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $item->nama_tagihan ?? '-' }}</td>
                                <td>{{ $item->periode ?? '-' }}</td>
                                <td>Rp{{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                                <td><span class="badge bg-info">{{ strtoupper($item->metode) }}</span></td>
                                <td>
                                    <span
                                        class="badge bg-{{ $item->status == 'lunas' ? 'success' : ($item->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y') }}</td>
                                <td>
                                    @if ($item->bukti_transfer)
                                        @if (Str::endsWith($item->bukti_transfer, ['.pdf']))
                                            <a href="{{ asset('storage/' . $item->bukti_transfer) }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary">Lihat PDF</a>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                onclick="previewBukti('{{ asset('storage/' . $item->bukti_transfer) }}')">
                                                Lihat Bukti
                                            </button>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function previewBukti(src) {
            Swal.fire({
                title: 'Preview Bukti Transfer',
                html: `<img src="${src}" class="img-fluid rounded" alt="Bukti Transfer" />`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 600
            });
        }
    </script>

@endsection
