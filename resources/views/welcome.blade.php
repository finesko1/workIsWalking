<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
{{--    <link rel="icon" href="{{ asset('faviconTMP.png') }}" type="image/x-icon">--}}
{{--    <title>Work is walking</title>--}}
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <title>Distance module</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div id="app"></div>
</body>
</html>
