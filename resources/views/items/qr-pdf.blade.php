<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 10px;
            /* Add some padding to page */
        }

        .box {
            border: 1px solid #000;
            padding: 8px;
            width: 280px;
            /* Smaller fixed width */
            height: 100px;
            /* Smaller fixed height */
            margin: auto;
            position: relative;
        }

        .qr-container {
            float: left;
            width: 30%;
            text-align: center;
        }

        .qr-container img {
            width: 100%;
            max-width: 80px;
            /* Smaller max size */
            height: auto;
        }

        .info-container {
            float: right;
            width: 68%;
            text-align: left;
            padding-top: 5px;
        }

        .info-container h3 {
            font-size: 14px;
            margin: 0 0 3px 0;
            line-height: 1.2;
        }

        .info-container p {
            font-size: 12px;
            margin: 0;
            color: #333;
        }

        /* Clearfix for floats */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>

    <div class="box clearfix">
        <div class="qr-container">
            <img src="{{ public_path('storage/' . $item->qr_code) }}">
        </div>
        <div class="info-container">
            <h3>{{ $item->name }}</h3>
            <p>{{ $item->serial_number }}</p>
        </div>
    </div>

</body>

</html>