<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 90vh;">
            <div class="col-11 col-sm-10 col-md-8 col-lg-5">
                
                <div class="glass-card p-4 p-md-5 position-relative overflow-hidden">
                    
                    <div style="position: absolute; top: -50px; left: -50px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>

                    <div class="text-center mb-4">
                        <div class="d-inline-block p-3 rounded-circle shadow-sm" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.2);">
                            <i class="fas fa-user-plus fa-3x text-white"></i>
                        </div>
                        <h3 class="fw-bold mt-3 text-white">New Admission</h3>
                        <p class="text-white-50 small">Register to access the portal</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger bg-transparent border-white text-white mb-4 p-2">
                            <ul class="mb-0 small list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="text-white small ms-2 mb-1 fw-bold">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control form-glass border-start-0" placeholder="John Doe" style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-white small ms-2 mb-1 fw-bold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" value="{{ old('email') }}" required class="form-control form-glass border-start-0" placeholder="student@school.com" style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-white small ms-2 mb-1 fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" required autocomplete="new-password" class="form-control form-glass border-start-0" placeholder="********" style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="text-white small ms-2 mb-1 fw-bold">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-end-0" style="border-radius: 10px 0 0 10px;"><i class="fas fa-key"></i></span>
                                <input type="password" name="password_confirmation" required class="form-control form-glass border-start-0" placeholder="********" style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>

                        <div class="d-grid">
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