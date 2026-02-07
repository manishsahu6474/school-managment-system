<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Student Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('js/custom.js') }}"></script>
</head>
<body>
    @if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Done!',
            text: "{{ session('success') }}",
            background: '#fff',
            // Shiny Blue 3D Button
            confirmButtonColor: '#00d2ff', 
            customClass: {
                popup: 'rounded-20 shadow-3d', // Aapki purani 3D classes
                confirmButton: 'btn-3d-success px-4 py-2' // Shiny Button
            }
        });
    });
</script>
@endif
<div>
    
    <div class="d-flex">
        {{-- SIDEBAR --}}
        @include('partials.sidebar')
        </div>

    <div class="main-content">
        {{-- NAVBAR --}}
         @include('partials.navbar')
        <div class="container-fluid p-4">
            @yield('content')
        </div>

        @include('partials.footer')
    </div>
    
</div>



</body>
</html>
