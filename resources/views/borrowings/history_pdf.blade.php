<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan History Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 6px; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<h2>Laporan Riwayat Peminjaman Barang</h2>

@if($from || $to)
<p><strong>Filter:</strong> {{ $from ?? 'Semua' }} → {{ $to ?? 'Semua' }}</p>
@endif

<p><strong>Generated at:</strong> {{ $generated_at }}</p>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Barang</th>
            <th>Peminjam</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Durasi</th>
        </tr>
    </thead>

    <tbody>
        @foreach($history as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->item->name }}</td>
                <td>{{ $item->borrower->name }}</td>
                <td>{{ $item->borrow_date }}</td>
                <td>{{ $item->return_date }}</td>

                @php
                    $borrowDate = \Carbon\Carbon::parse($item->borrow_date);
                    $returnDate = \Carbon\Carbon::parse($item->return_date);
                    $diff = $borrowDate->diff($returnDate);
                @endphp

                <td>{{ $diff->h }} jam {{ $diff->i }} menit</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
