<x-guest-layout>
    <style>
        /* Card ko height se bahar na jaane dene ke liye */
        .glass-card {
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
        }
        /* Input heights ko thoda compact karne ke liye */
        .form-control, .input-group-text {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        /* Mobile responsive fix */
        @media (max-height: 800px) {
            .row { padding-top: 1rem !important; padding-bottom: 1rem !important; }
            .fa-3x { font-size: 2rem !important; }
            h3 { font-size: 1.25rem !important; }
        }
    </style>

    <div class="container">
        <div class="row justify-content-center align-items-center py-4" style="min-height: 100vh;">
            <div class="col-11 col-sm-10 col-md-8 col-lg-5">
                
                <div class="glass-card p-3 p-md-4 position-relative overflow-hidden">
                    
                    <div class="text-center mb-3">
                        <div class="d-inline-block p-2 rounded-circle shadow-sm" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.2);">
                            <i class="fas fa-user-plus fa-3x text-white"></i>
                        </div>
                        <h3 class="fw-bold mt-2 text-white">New Admission</h3>
                        <p class="text-white-50 small mb-0">Register to access the portal</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger bg-transparent border-white text-white mb-3 p-2">
                            <ul class="mb-0 small list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-2">
                            <label class="text-white small ms-1 mb-1 fw-bold">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control form-glass border-start-0" placeholder="John Doe" style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="text-white small ms-1 mb-1 fw-bold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" value="{{ old('email') }}" required class="form-control form-glass border-start-0" placeholder="student@school.com" style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="text-white small ms-1 mb-1 fw-bold">Select Class</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-end-0" style="border-radius: 10px 0 0 10px;">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                                <select name="class" required class="form-control form-glass border-start-0" style="border-radius: 0 10px 10px 0; background: rgba(255,255,255,0.1); color: white;">
                                    <option value="" disabled selected class="text-dark">-- Select Your Class --</option>
                                    <option value="9th" class="text-dark">9th Grade</option>
                                    <option value="10th" class="text-dark">10th Grade</option>
                                    <option value="11th" class="text-dark">11th Grade</option>
                                    <option value="12th" class="text-dark">12th Grade</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="text-white small ms-1 mb-1 fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" required autocomplete="new-password" class="form-control form-glass border-start-0" placeholder="********" style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-white small ms-1 mb-1 fw-bold">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fas fa-key"></i></span>
                                <input type="password" name="password_confirmation" required class="form-control form-glass border-start-0" placeholder="********" style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>
                        
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-3d py-2 fs-5">
                                Register Now <i class="fas fa-user-check ms-2"></i>
                            </button>
                        </div>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-white small text-decoration-none">
                                Already registered? <span class="fw-bold text-warning">Login here</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>