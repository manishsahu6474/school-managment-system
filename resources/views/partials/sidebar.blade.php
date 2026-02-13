<div class="sidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-center" style="height: 60px;">
         <a class="sidebar-brand d-flex align-items-center justify-content-center text-decoration-none" href="{{ url('/') }}">
            <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-graduation-cap fa-2x text-white"></i> </div>
            <div class="sidebar-brand-text mx-3 text-white fw-bold">SMS PORTAL</div> 
        </a>
    </div>
    <ul class="nav flex-column sidebar-menu">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" 
               class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }}">
             <i class="fas fa-chart-line me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('students.index') }}"
               class="nav-link text-white {{ request()->routeIs('students.*') ? 'active' : '' }}">
             <i class="fas fa-user-graduate me-2"></i> Students
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-white {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher me-2"></i> Teachers
            </a>
        </li>
        <li class="nav-item">                                                   
            <a href="#" class="nav-link text-white {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                <i class="fas fa-school me-2"></i> Classes
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-white {{ request()->routeIs('subject.*') ? 'active' : '' }}">
                <i class="fas fa-book-reader me-2"></i> Subjects
            </a>
        </li>
   </ul>
</div>
