<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>QR Code Labels</title>
    <style>
        @page {
            margin: 10mm;
            size: A4 portrait;
        }

        body {
            font-family: sans-serif;
            font-size: 9pt;
        }

        .items-container {
            width: 100%;
        }

        .label-wrapper {
            float: left;
            width: 32%;
            /* 3 columns roughly */
            margin-right: 1.3%;
            margin-bottom: 10mm;
            height: 90mm;
        }

        .label-wrapper:nth-child(3n) {
            margin-right: 0;
            clear: right;
        }

        .label {
            border: 2px solid #333;
            padding: 5mm;
            text-align: center;
            height: 100%;
            background: #fff;
            box-sizing: border-box;
        }

        .header {
            font-weight: bold;
            font-size: 10pt;
            color: #000;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .qr-img {
            width: 45mm;
            height: 45mm;
            margin: 0 auto;
            display: block;
        }

        .info {
            margin-top: 10px;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }

        .name {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 5px;
            overflow: hidden;
            height: 30px;
        }

        .meta {
            font-family: monospace;
            font-size: 8pt;
            color: #333;
        }

        .footer {
            font-size: 7pt;
            color: #777;
            margin-top: 5px;
            font-style: italic;
        }

        .page-break {
            page-break-after: always;
            clear: both;
        }
    </style>
</head>

<body>
    <div class="items-container">
        @foreach($items as $item)
            <div class="label-wrapper">
                <div class="label">
                    <div class="header">
                        Lab Biomedis STEI
                    </div>

                    <div class="qr-box">
                        @if($item->qr_code && file_exists(public_path('storage/' . $item->qr_code)))
                            <img src="{{ public_path('storage/' . $item->qr_code) }}" class="qr-img" alt="QR">
                        @else
                            {{-- Inline Base64 SVG --}}
                            <img src="data:image/svg+xml;base64,{{ base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(150)->generate($item->serial_number)) }}"
                                class="qr-img" alt="QR">
                        @endif
                    </div>

                    <div class="info">
                        <div class="name">
                            {{ $item->name }}
                        </div>
                        <div class="meta">
                            SN: {{$item->serial_number}}<br>
                            @if($item->asset_number) Asset: {{$item->asset_number}} @endif
                        </div>
                        <div class="footer">
                            {{ $item->room->name ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            @if($loop->iteration % 15 == 0 && !$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>
</body>

</html>