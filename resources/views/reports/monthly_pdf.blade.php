<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Peminjaman {{ $month }} {{ $year }}</title>
    <style>
        @page {
            margin: 15mm;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2563EB;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 16pt;
            color: #1E40AF;
        }

        .header h2 {
            margin: 5px 0 0 0;
            font-size: 12pt;
            color: #555;
            font-weight: normal;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 9pt;
            color: #666;
        }

        .meta {
            text-align: right;
            margin-bottom: 15px;
            font-size: 9pt;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #2563EB;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
        }

        td {
            padding: 6px 8px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 9pt;
        }

        tr:nth-child(even) {
            background-color: #F9FAFB;
        }

        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
            display: inline-block;
        }

        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .status-approved {
            background-color: #D1FAE5;
            color: #065F46;
        }

        .status-active {
            background-color: #DBEAFE;
            color: #1E40AF;
        }

        .status-returned {
            background-color: #E5E7EB;
            color: #374151;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #999;
            border-top: 1px solid #E5E7EB;
            padding-top: 10px;
        }

        .summary {
            background-color: #F3F4F6;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #2563EB;
        }

        .summary strong {
            color: #1E40AF;
        }
    </style>
</head>

<body>
    <!-- HEADER / KOP SURAT -->
    <div class="header">
        <h1>LABORATORIUM BIOMEDIS</h1>
        <h2>Sekolah Teknik Elektro dan Informatika - Institut Teknologi Bandung</h2>
        <p>Jl. Ganesha No. 10, Bandung 40132 | Tel: (022) 2501087</p>
    </div>

    <!-- META INFO -->
    <div class="meta">
        <strong>Laporan Peminjaman Barang</strong><br>
        Periode: <strong>{{ $month }} {{ $year }}</strong><br>
        Dicetak: {{ $generated_at }}
    </div>

    <!-- SUMMARY -->
    <div class="summary">
        <strong>Ringkasan:</strong> Total {{ $loans->count() }} transaksi peminjaman pada bulan ini.
    </div>

    <!-- TABLE -->
    @if($loans->isEmpty())
        <p style="text-align: center; color: #999; padding: 40px 0;">
            Tidak ada data peminjaman pada periode ini.
        </p>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Peminjam</th>
                    <th style="width: 25%;">Barang</th>
                    <th style="width: 10%;">Ruangan</th>
                    <th style="width: 12%;">Tgl Pinjam</th>
                    <th style="width: 12%;">Tgl Kembali</th>
                    <th style="width: 11%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loans as $index => $loan)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>
                            {{ $loan->user->name ?? '-' }}<br>
                            <small style="color: #666;">{{ $loan->user->email ?? '-' }}</small>
                        </td>
                        <td>{{ $loan->item->name ?? '-' }}</td>
                        <td>{{ $loan->item->room->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') }}</td>
                        <td>
                            <span class="status-badge status-{{ $loan->status }}">
                                {{ ucfirst($loan->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- FOOTER -->
    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh Sistem Inventaris Lab Biomedis STEI ITB
    </div>
</body>

</html>