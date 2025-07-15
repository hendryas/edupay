<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kwitansi Pembayaran</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .kwitansi-box {
            border: 1px solid #000;
            padding: 20px;
            width: 100%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="kwitansi-box">
        <h2>Kwitansi Pembayaran</h2>
        <div class="info">Nama Siswa: <strong>{{ $data->nama_siswa }}</strong></div>
        <div class="info">Tagihan: {{ $data->nama_tagihan }}</div>
        <div class="info">Periode: {{ $data->periode }}</div>
        <div class="info">Metode: {{ strtoupper($data->metode) }}</div>
        <div class="info">Tanggal Bayar: {{ \Carbon\Carbon::parse($data->tanggal_bayar)->format('d M Y') }}</div>
        <div class="info">Jumlah Bayar: <strong>Rp{{ number_format($data->jumlah_bayar, 0, ',', '.') }}</strong></div>

        <p style="margin-top: 40px;">Bendahara,</p>
        <p style="margin-top: 60px;">_______________________</p>
    </div>
</body>

</html>
