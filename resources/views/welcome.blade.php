<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('app.name')}}WELCOME </title>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/167/167707.png">
    
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
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="ms-auto d-flex gap-3 mt-3 mt-lg-0">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-3d">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-glass">Log in</a>
                        <a href="{{ route('register') }}" class="btn btn-3d">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section class="d-flex align-items-center" style="min-height: 100vh; padding-top: 80px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5">
                    <div class="glass-card p-5">
                        <span class="badge bg-light text-primary mb-3 px-3 py-2 rounded-pill"> Live</span>
                        <h1 class="display-4 fw-bold mb-3">Future of <br><span style="color: #ffecd2;">Smart Education</span></h1>
                        <p class="lead mb-4">Manage Student Data, Teacher Attendance, and Results with a stunning 3D interface. SEO friendly and Mobile responsive.</p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="{{ Auth::check() ? url('/dashboard') : route('register') }}" class="btn btn-3d btn-lg">Get Started</a>
                            <a href="#features" class="btn btn-outline-glass btn-lg">View Features</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="glass-card p-5 d-inline-block">
                        <i class="fas fa-university fa-10x text-white opacity-75 hero-img-animate"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container mb-5">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="glass-card p-4">
                    <div class="stat-number">500+</div>
                    <div class="small opacity-75">Students Enrolled</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4">
                    <div class="stat-number">50+</div>
                    <div class="small opacity-75">Expert Teachers</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4">
                    <div class="stat-number">100%</div>
                    <div class="small opacity-75">Digital Reports</div>
                </div>
            </div>
        </div>
    </div>

    <div class="container pb-5" id="features">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="glass-card p-4 text-center h-100">
                    <i class="fas fa-users fa-3x text-warning mb-3"></i>
                    <h4>Student Management</h4>
                    <p class="small opacity-75">Admission se lekar result tak sab kuch ek hi jagah manage karein.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4 text-center h-100">
                    <i class="fas fa-chalkboard-teacher fa-3x text-info mb-3"></i>
                    <h4>Teacher Portal</h4>
                    <p class="small opacity-75">Teachers ke liye alag dashboard aur daily attendance management.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-4 text-center h-100">
                    <i class="fas fa-shield-alt fa-3x text-success mb-3"></i>
                    <h4>Secure Data</h4>
                    <p class="small opacity-75">Role based security (Admin, Teacher, Student) data ko safe rakhti hai.</p>
                </div>
            </div>
        </div>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>