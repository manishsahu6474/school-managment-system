<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} Guest</title>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/167/167707.png ">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <script src="{{ asset('js/custom.js') }}"></script>
    <style>
        body {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <script>
        @if (session('success'))
            showSuccessAlert("{{ session('success') }}");
        @endif

        @if (session('error'))
            Swal.fire('Error!', "{{ session('error') }}", 'error');
        @endif
    </script>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="w-100">
        {{ $slot }}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
