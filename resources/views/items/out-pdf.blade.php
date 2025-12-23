<!DOCTYPE html>
<html>
<head>
    <title>Surat Jalan - {{ $item->serial_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 12px; color: #666; }
        
        .meta-table { width: 100%; margin-bottom: 20px; }
        .meta-table td { padding: 5px; vertical-align: top; }
        .label { font-weight: bold; width: 150px; }

        .content-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .content-table th, .content-table td { border: 1px solid #333; padding: 10px; text-align: left; }
        .content-table th { background-color: #f0f0f0; }

        .signature-section { width: 100%; margin-top: 50px; }
        .signature-box { width: 45%; float: left; text-align: center; }
        .signature-box p { margin-bottom: 70px; font-weight: bold; } /* Jarak untuk tanda tangan */
        .signature-line { border-top: 1px solid #333; width: 80%; margin: 0 auto; display: block; }
        
        /* Clearfix */
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="header">
        <h1>Surat Jalan Pengeluaran Barang</h1>
        <p>Dokumen ini adalah bukti resmi perpindahan aset/inventaris perusahaan.</p>
        <p>Dicetak pada: {{ date('d F Y, H:i') }}</p>
    </div>

    {{-- INFORMASI DASAR --}}
    <table class="meta-table">
        <tr>
            <td class="label">Nomor Surat</td>
            <td>: {{ $log->reference_number ?? '-' }}</td> {{-- Kalau kosong strip --}}
        </tr>
        <tr>
            <td class="label">Tanggal Keluar</td>
            <td>: {{ $log->out_date->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Penerima / Tujuan</td>
            <td>: <strong>{{ $log->recipient_name }}</strong></td>
        </tr>
    </table>

    {{-- TABEL DETAIL BARANG --}}
    <table class="content-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang / Deskripsi</th>
                <th>Serial Number / Asset</th>
                <th>Kondisi</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td>
                    <strong>{{ $item->name }}</strong><br>
                    <small>Kategori: {{ $item->categories->pluck('name')->join(', ') }}</small>
                </td>
                <td>
                    SN: {{ $item->serial_number }}<br>
                    Asset: {{ $item->asset_number ?? '-' }}
                </td>
                <td>{{ ucfirst($item->condition) }}</td>
                <td style="text-align: center;">{{ $item->quantity }}</td>
            </tr>
        </tbody>
    </table>

    {{-- KETERANGAN TAMBAHAN --}}
    <div style="margin-bottom: 30px;">
        <strong>Keterangan / Alasan:</strong><br>
        <p style="border: 1px dashed #ccc; padding: 10px; background-color: #fafafa;">
            {{ $log->reason ?? 'Tidak ada keterangan tambahan.' }}
        </p>
    </div>

    {{-- KOLOM TANDA TANGAN --}}
    <div class="signature-section clearfix">
        <div class="signature-box">
            <p>Diserahkan Oleh (Admin),</p>
            <br><br>
            <span class="signature-line"></span>
            <span>( ..................................... )</span>
        </div>

        <div class="signature-box" style="float: right;">
            <p>Diterima Oleh,</p>
            <br><br>
            <span class="signature-line"></span>
            <span>( {{ $log->recipient_name }} )</span>
        </div>
    </div>

</body>
</html>