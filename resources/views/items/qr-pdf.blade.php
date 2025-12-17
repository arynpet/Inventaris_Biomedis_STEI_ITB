<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; text-align: center; }
        .box { border: 1px solid #000; padding: 20px; width: 300px; margin: auto; }
        img { width: 200px; }
    </style>
</head>
<body>

    <div class="box">
        <h3>{{ $item->name }}</h3>
        <p>{{ $item->serial_number }}</p>

        <img src="{{ public_path('storage/'.$item->qr_code) }}">
    </div>

</body>
</html>
