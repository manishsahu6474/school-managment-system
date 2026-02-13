<x-app-layout>
    <div class="py-4">
        <div class="container">
            <div class="card card-morphism border-0">
                <div class="card-header morphism-header text-white">
                <h3 class="mb-0">Welcome to Student Management System</h3>
                </div>
                <div class="card-body p-5">
                    <h4 class="text-dark">Hello, {{ Auth::user()->name }}!</h4>
                    <p>Aap yahan se Students aur Teachers ko manage kar sakte hain.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('students.index') }}" class="text-decoration-none">
                <div class="card card-3d border-0 shadow-sm h-100" style="background: linear-gradient(145deg, #ffffff, #f0f0f0);">
                    <div class="card-body text-center p-4">
                        <div class="icon-box mb-3 mx-auto shadow-sm" style="background: #e7f1ff; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user-graduate fa-2x text-primary"></i>
                        </div>
                        <h6 class="text-uppercase fw-bold text-muted mb-1">Total Students</h6>
                        <h2 class="fw-bold text-dark mb-0">{{ $totalStudents }}</h2>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-3d border-0 shadow-sm h-100" style="background: linear-gradient(145deg, #ffffff, #f0f0f0);">
                <div class="card-body text-center p-4">
                    <div class="icon-box mb-3 mx-auto shadow-sm" style="background: #eaf8ef; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
                    </div>
                    <h6 class="text-uppercase fw-bold text-muted mb-1">Total Teachers</h6>
                    <h2 class="fw-bold text-dark mb-0">{{ $totalTeachers }}</h2>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-3d border-0 shadow-sm h-100" style="background: linear-gradient(145deg, #ffffff, #f0f0f0);">
                <div class="card-body text-center p-4">
                    <div class="icon-box mb-3 mx-auto shadow-sm" style="background: #fff8e6; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-school fa-2x text-warning"></i>
                    </div>
                    <h6 class="text-uppercase fw-bold text-muted mb-1">Total Classes</h6>
                    <h2 class="fw-bold text-dark mb-0">{{ $totalClasses }}</h2>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-3d border-0 shadow-sm h-100" style="background: linear-gradient(145deg, #ffffff, #f0f0f0);">
                <div class="card-body text-center p-4">
                    <div class="icon-box mb-3 mx-auto shadow-sm" style="background: #fdf2f2; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-book fa-2x text-danger"></i>
                    </div>
                    <h6 class="text-uppercase fw-bold text-muted mb-1">Total Subjects</h6>
                    <h2 class="fw-bold text-dark mb-0">{{ $totalSubjects }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>