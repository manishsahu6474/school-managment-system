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
    <style>
        body {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            min-height: 100dvh;
            justify-content: center;

        }
    </style>
</head>

<body>
        <div class="w-100">
            {{ $slot }}
            <footer class="py-5 mt-5">
                <div class="container text-center">
                    <div class="mb-4">
                        <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                        <h5 class="fw-bold">SMS Portal</h5>
                        <p class="small opacity-50">© 2026 Student Management System. All rights reserved.</p>
                    </div>
                    <div class="d-flex justify-content-center gap-4">
                        <a href="#" class="text-white opacity-75"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white opacity-75"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white opacity-75"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </footer>
        </div>
</body>

</html>
