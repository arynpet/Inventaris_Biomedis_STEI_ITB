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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
        }

        .label-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8mm;
            width: 100%;
        }

        .label {
            border: 2px solid #333;
            padding: 6mm;
            text-align: center;
            page-break-inside: avoid;
            background: #fff;
            border-radius: 8px;
            height: 90mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .label-header {
            font-weight: bold;
            font-size: 10pt;
            color: #1E40AF;
            margin-bottom: 4mm;
            padding-bottom: 3mm;
            border-bottom: 2px solid #3B82F6;
        }

        .qr-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-center;
            padding: 2mm 0;
        }

        .qr-container img {
            max-width: 50mm;
            max-height: 50mm;
            width: 100%;
            height: auto;
        }

        .item-info {
            margin-top: 3mm;
            padding-top: 3mm;
            border-top: 1px solid #E5E7EB;
        }

        .item-name {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 2mm;
            color: #1F2937;
            min-height: 12mm;
            display: flex;
            align-items: center;
            justify-center;
            text-align: center;
        }

        .item-code {
            font-family: 'Courier New', monospace;
            font-size: 8pt;
            color: #6B7280;
            margin-bottom: 1mm;
        }

        .item-location {
            font-size: 7pt;
            color: #9CA3AF;
            font-style: italic;
        }

        .label-footer {
            font-size: 6pt;
            color: #9CA3AF;
            margin-top: 2mm;
            padding-top: 2mm;
            border-top: 1px dashed #E5E7EB;
        }

        /* Print-specific adjustments */
        @media print {
            .label {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="label-grid">
        @foreach($items as $item)
            <div class="label">
                <div class="label-header">
                    LAB BIOMEDIS STEI ITB
                </div>

                <div class="qr-container">
                    @if($item->qr_code && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->qr_code))
                        {{-- Use existing QR code --}}
                        <img src="{{ public_path('storage/' . $item->qr_code) }}" alt="QR Code">
                    @else
                        {{-- Generate QR code inline --}}
                        <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(200)->generate($item->serial_number)) }}"
                            alt="QR Code">
                    @endif
                </div>

                <div class="item-info">
                    <div class="item-name">
                        {{ \Illuminate\Support\Str::limit($item->name, 40, '...') }}
                    </div>

                    <div class="item-code">
                        SN: {{ $item->serial_number }}
                    </div>

                    @if($item->asset_number)
                        <div class="item-code">
                            Asset: {{ $item->asset_number }}
                        </div>
                    @endif

                    @if($item->room)
                        <div class="item-location">
                            📍 {{ $item->room->name }}
                        </div>
                    @endif
                </div>

                <div class="label-footer">
                    Inventaris Lab Biomedis {{ date('Y') }}
                </div>
            </div>

            {{-- Page break after every 15 labels (5 rows x 3 columns) --}}
            @if(($loop->iteration % 15 == 0) && !$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    </div>
</body>

</html>