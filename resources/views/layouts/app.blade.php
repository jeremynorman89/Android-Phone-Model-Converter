<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
    <title>Android Phone Model Converter</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @livewireStyles
</head>
<body>
<section class="section">
    <div class="container">
        {{ $slot }}
    </div>
</section>
@livewireScripts
</body>
</html>
