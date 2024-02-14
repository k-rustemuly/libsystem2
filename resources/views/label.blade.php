<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    {{-- <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'> --}}
    <style>
        /* @page {
            size: 58mm 40mm;
        } */
        @font-face {
            font-family: 'Arial Kz';
            src: url({{ storage_path('fonts\kzarialbold.ttf') }}) format("truetype");
        }
        .page-break {
            page-break-after: always;
        }
        .label {
            padding: 5px;
        }
        img{
            width: 100%;
            height: 45px;
        }
        p {
            line-height: 1;
        }
        .code {
            text-align: center;
        }
        * {
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: "Arial Kz";
            font-size: 15px;
        }
    </style>
</head>
<body>
    @foreach($datas as $data)
        <div class="label">
            <p>{{ $data['name'] }}</p>
            <img src="data:image/png;base64,{{ $data['barcode'] }}">
            <p class="code">{{ $data['code'] }}</p>
            <p>ISBN: {{ $data['isbn'] }}</p>
        </div>
        @if(!$loop->last)
            <div class='page-break'></div>
        @endif
    @endforeach
</body>
</html>
