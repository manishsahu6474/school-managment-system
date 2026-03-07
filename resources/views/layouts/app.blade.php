<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">
    <title>
        @yield('title', ucwords(str_replace(['admin.', '.', 'index', 'create', 'edit'], ['', ' ', ' List', ' Add', ' Edit'], Route::currentRouteName())))
        | {{ config('app.name') }}
    </title>

    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/167/167707.png">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>

</head>

<body class="bg-light">

    <div class="wrapper d-flex">
        <aside id="sidebar" role="navigation" aria-label="Main Sidebar">
            @include('partials.sidebar')
        </aside>

        <div class="main-content w-100 min-vh-100 d-flex flex-column">
            <header>
                @include('partials.navbar')
            </header>

            <main id="content" role="main" class="flex-grow-1 p-3 p-md-4 mt-2">
                @if (isset($header))
                    <section class="content-header mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{{ $header }}</h1>
                    </section>
                @endif

                <article class="content-body container-fluid">
                    {{ $slot }}
                </article>
            </main>

            <footer class="mt-auto ">
                @include('partials.footer')
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/custom.js') }}"></script>



    <script>
       document.addEventListener('DOMContentLoaded', function () {
        // 1. Success Message Trigger
        @if (session('success'))
            // JS function ko call karega
            showSuccessAlert("{{ session('success') }}");
        @endif

        // 2. Info Message Trigger
        @if (session('info'))
            showinfoAlert("{{ session('info') }}");
        @endif

        // Browser history clean up (Taaki refresh par baar-baar alert na aaye)
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    });
    </script>

    @stack('scripts')
</body>

</html>
