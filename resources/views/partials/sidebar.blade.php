<div class="sidebar bg-dark text-white vh-100 p-3" style="width:250px;">
    <h4 class="text-center mb-4">
        <i class="bi bi-mortarboard-fill"></i> WELCOME
    </h4>

    <ul class="nav flex-column sidebar-menu">

        <li class="nav-item">
            <a href="{{ route('dashboard') }}" 
            class="nav-link text-white {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('students.index') }}"
              class="nav-link text-white {{ request()->routeIs('students.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill me-2"></i> Students
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link text-white">
                <i class="bi bi-person-badge-fill me-2"></i> Teachers
            </a>
        </li>

        <li class="nav-item">                                                   
            <a href="#" class="nav-link text-white">
                <i class="bi bi-building me-2"></i> Classes
            </a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link text-white">
                <i class="bi bi-book-fill me-2"></i> Subjects
            </a>
        </li>
   </ul>
</div>