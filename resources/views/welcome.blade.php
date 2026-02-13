<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body>

    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-glass fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="#">
                <i class="fas fa-graduation-cap me-2"></i> SMS Portal
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto mt-3 mt-lg-0">
                    @if (Route::has('login'))
                        <div class="d-flex gap-3 flex-column flex-lg-row">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-3d">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-glass">Log in</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn btn-3d">Register</a>
                                @endif
                            @endauth
                        </div>
                        
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section class="d-flex align-items-center justify-content-center" style="min-height: 100vh; padding-top: 80px;">
        <div class="container">
            <div class="row align-items-center">
                
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="glass-card p-5">
                        <span class="badge bg-light text-primary mb-3 px-3 py-2 rounded-pill">Version 2.0 Live</span>
                        <h1 class="display-4 fw-bold mb-3">
                            Future of <br> 
                            <span style="color: #ffecd2; text-shadow: 0 2px 10px rgba(0,0,0,0.2);">Smart Education</span>
                        </h1>
                        <p class="lead mb-4" style="color: rgba(255,255,255,0.9);">
                            Manage Student Data, Teacher Attendance, and Results with a stunning 3D interface.
                        </p>
                        
                        <div class="d-flex gap-3 flex-wrap">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="btn btn-3d btn-lg">
                                    <i class="fas fa-rocket me-2"></i> Go to Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-3d btn-lg">
                                    Get Started
                                </a>
                                <a href="#features" class="btn btn-outline-glass btn-lg">
                                    <i class="fas fa-play me-2"></i> Demo
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 text-center">
                <div class="glass-card p-5 d-inline-block">
                    <i class="fas fa-university fa-10x text-white opacity-75  hero-img-animate"></i>
                </div>
                </div>

            </div>
        </div>
    </section>

    <div class="container pb-5" id="features">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="glass-card p-4 text-center h-100">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x text-warning"></i>
                    </div>
                    <h4>Student Management</h4>
                    <p class="small opacity-75">Admission se lekar result tak sab kuch manage karein.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4 text-center h-100">
                    <div class="mb-3">
                        <i class="fas fa-chalkboard-teacher fa-3x text-info"></i>
                    </div>
                    <h4>Teacher Portal</h4>
                    <p class="small opacity-75">Teachers ke liye alag login aur attendance system.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4 text-center h-100">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt fa-3x text-success"></i>
                    </div>
                    <h4>Secure & Fast</h4>
                    <p class="small opacity-75">Role based security (Admin, Teacher, Student).</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>