<nav class="navbar navbar-expand-lg navbar-light bg-primary py-2 px-3 shadow-sm border-bottom border-white-50">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <div id="menu-toggle" class="text-white fs-4 d-flex align-items-center justify-content-center me-2">
            <i class="fas fa-bars"></i>
        </div>
        <div class="navbar-brand-centered h2">
            <h2 class="fs-5 m-0 text-white fw-bold text-uppercase tracking-wider">
                Student Management
            </h2>
        </div>
        <div class="dropdown ms-auto">
            <a class="nav-link dropdown-toggle p-0 d-flex align-items-center shadow-none" href="#" id="profileDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                     <img src="https://ui-avatars.com/api/?name=Admin&background=224abe&color=fff&bold=true" 
                     class="rounded-circle border border-2 border-white-50 shadow-sm" 
                     width="38" height="38" alt="Admin">
            </a>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2 py-2 animate slideIn" aria-labelledby="profileDrop">
                <li class="px-3 py-2 border-bottom mb-2">
                    <p class="mb-0 small text-muted">Signed in as {{ Auth::user()->name }}</p>
                    <p class="mb-0 fw-bold">Admin User</p>
                </li>
                <li><a class="dropdown-item py-2" href="#"><i class="fas fa-user-circle me-2 text-primary"></i> My Profile</a></li>
                <li><a class="dropdown-item py-2" href="#"><i class="fas fa-cog me-2 text-primary"></i> Settings</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item py-2 text-danger fw-bold" href="javascript:void(0)" onclick="logoutConfirm()">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
                 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
                </form>   
            </a></li>
            </ul>
        </div>
    </div>
</nav>