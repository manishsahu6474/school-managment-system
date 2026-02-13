<x-guest-layout>
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="col-11 col-sm-10 col-md-8 col-lg-5">
                
                <div class="glass-card p-4 p-md-5 position-relative overflow-hidden">
                    
                    <div style="position: absolute; top: -50px; right: -50px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>

                    <div class="text-center mb-4">
                        <div class="d-inline-block p-3 rounded-circle shadow-sm" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.2);">
                            <i class="fas fa-school fa-3x text-white"></i>
                        </div>
                        <h3 class="fw-bold mt-3 text-white" style="letter-spacing: 1px;">Login Portal</h3>
                        <p class="text-white-50 small">Apne account mein login karein</p>
                    </div>

                    <x-auth-session-status class="mb-3 alert alert-success bg-transparent border-white text-white" :status="session('status')" />

                    @if ($errors->any())
                        <div class="alert alert-danger bg-transparent border-white text-white mb-4 p-2">
                            <ul class="mb-0 small list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf <div class="mb-4">
                            <label class="text-white small ms-2 mb-1 fw-bold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-end-0" style="border-color: rgba(255,255,255,0.3); border-radius: 10px 0 0 10px;">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus 
                                       class="form-control form-glass border-start-0" 
                                       placeholder="admin@school.com"
                                       style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="text-white small ms-2 mb-1 fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-white border-end-0" style="border-color: rgba(255,255,255,0.3); border-radius: 10px 0 0 10px;">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" name="password" required autocomplete="current-password"
                                       class="form-control form-glass border-start-0" 
                                       placeholder="********"
                                       style="border-radius: 0 10px 10px 0;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input id="remember_me" type="checkbox" class="form-check-input" name="remember" 
                                       style="background-color: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.5);">
                                <label for="remember_me" class="form-check-label text-white small">Remember me</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-white small text-decoration-none fw-bold" href="{{ route('password.request') }}" style="text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-3d py-2 fs-5">
                                Secure Login <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                    
                </div>
                <div class="text-center mt-4">
                    <a href="/" class="text-white-50 text-decoration-none small">
                        <i class="fas fa-arrow-left me-1"></i> Back to Home
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>