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
            font-family: "Helvetica", "Arial", sans-serif;
            margin: 0;
            padding: 0;
        }

        table.main-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 5px;
            /* Very tight gap */
            table-layout: fixed;
            margin: 0;
        }

        td.grid-cell {
            width: 20%;
            /* 5 Columns */
            vertical-align: top;
            padding: 0;
        }

        /* Compact Vertical Asset Tag */
        .label-box {
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #fff;
            height: 140px;
            /* Shorter fixed height */
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        /* Compact Header */
        .label-header {
            background-color: #2c3e50;
            color: #ffffff;
            font-size: 6.5pt;
            /* Smaller font */
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 2px;
            line-height: 1.1;
            min-height: 22px;

            /* Center vertically/horizontally */
            display: block;
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .label-content {
            padding: 4px;
        }

        /* Smaller QR */
        .qr-box {
            margin: 4px auto;
            width: 55px;
            height: 55px;
        }

        .qr-img {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* Details */
        .info-area {
            margin-top: 4px;
        }

        .sn-text {
            font-family: "Courier New", Courier, monospace;
            font-size: 7.5pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 2px;
            word-wrap: break-word;
        }

        .meta-text {
            font-size: 7pt;
            color: #666;
            margin-bottom: 2px;
        }

        .footer {
            font-size: 4.5pt;
            color: #aaa;
            text-transform: uppercase;
            margin-top: 5px;
            letter-spacing: 0.5px;
        }

        .page-break {
            page-break-after: always;
            clear: both;
        }
    </style>
</head>

<body>

    <table class="main-grid">
        @foreach($items->chunk(5) as $chunk)
            <tr>
                @foreach($chunk as $item)
                    <td class="grid-cell">
                        <div class="label-box">
                            <!-- Header: Asset Name -->
                            <div class="label-header">
                                {{ Str::limit($item->name, 35) }}
                            </div>

                            <div class="label-content">
                                <!-- QR Code Centered -->
                                <div class="qr-box">
                                    @if($item->qr_code && file_exists(public_path('storage/' . $item->qr_code)))
                                        <img src="{{ public_path('storage/' . $item->qr_code) }}" class="qr-img" alt="QR">
                                    @else
                                        <img src="data:image/svg+xml;base64,{{ base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(100)->errorCorrection('H')->generate($item->serial_number)) }}"
                                            class="qr-img" alt="QR">
                                    @endif
                                </div>

                                <!-- Stacked Info -->
                                <div class="info-area">
                                    <div class="sn-text">{{ $item->serial_number }}</div>
                                    <div class="footer">Lab Biomedis</div>
                                </div>
                            </div>
                        </div>
                    </td>
                @endforeach

                {{-- Fill empty cells --}}
                @for($i = $chunk->count(); $i < 5; $i++)
                    <td class="grid-cell"></td>
                @endfor
            </tr>

            {{-- Page Break Logic: 7 rows = 35 items per page --}}
            @if($loop->iteration % 7 == 0 && !$loop->last)
                </table>
                <div class="page-break"></div>
                <table class="main-grid">
            @endif
        @endforeach
    </table>

</body>

</html>