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
                <a href="{{ route('admin.students.index') }}" class="text-decoration-none">
                    <div class="card card-3d border-0 shadow-sm h-100"
                        style="background: linear-gradient(145deg, #ffffff, #f0f0f0);">
                        <div class="card-body text-center p-4">
                            <div class="icon-box mb-3 mx-auto shadow-sm"
                                style="background: #e7f1ff; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-graduate fa-2x text-primary"></i>
                            </div>
                            <h6 class="text-uppercase fw-bold text-muted mb-1">Total Students</h6>
                            <h2 class="fw-bold text-dark mb-0">{{ $totalStudents }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6">
                <a href="{{ route('admin.teachers.index') }}" class="text-decoration-none">
                    <div class="card card-3d border-0 shadow-sm h-100"
                        style="background: linear-gradient(145deg, #ffffff, #f0f0f0);">
                        <div class="card-body text-center p-4">
                            <div class="icon-box mb-3 mx-auto shadow-sm"
                                style="background: #eaf8ef; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-chalkboard-teacher fa-2x text-success"></i>
                            </div>
                            <h6 class="text-uppercase fw-bold text-muted mb-1">Total Teachers</h6>
                            <h2 class="fw-bold text-dark mb-0">{{ $totalTeachers }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6">
                <a href="{{ route('admin.classes.index') }}" class="text-decoration-none">
                    <div class="card card-3d border-0 shadow-sm h-100"
                        style="background: linear-gradient(145deg, #ffffff, #f0f0f0);">
                        <div class="card-body text-center p-4">
                            <div class="icon-box mb-3 mx-auto shadow-sm"
                                style="background: #fff8e6; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-school fa-2x text-warning"></i>
                            </div>
                            <h6 class="text-uppercase fw-bold text-muted mb-1">Total Classes</h6>
                            <h2 class="fw-bold text-dark mb-0">{{ $totalClasses }}</h2>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card card-3d border-0 shadow-sm h-100"
                    style="background: linear-gradient(145deg, #ffffff, #f0f0f0);">
                    <div class="card-body text-center p-4">
                        <div class="icon-box mb-3 mx-auto shadow-sm"
                            style="background: #fdf2f2; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-book fa-2x text-danger"></i>
                        </div>
                        <h6 class="text-uppercase fw-bold text-muted mb-1">Total Subjects</h6>
                        <h2 class="fw-bold text-dark mb-0">{{ $totalSubjects }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (isset($stats))
        <input type="hidden" id="stats-data" value="{{ json_encode($stats) }}">
    @endif

    <div class="dashboard-wrapper py-4">
        <div class="row g-4">
            <div class="col-lg-6 col-md-12">
                <div class="card apple-card p-4 h-100 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold m-0"><i class="fas fa-user-graduate me-2 text-primary"></i> Student Analytics
                        </h5>
                        <span class="badge bg-light text-dark rounded-pill shadow-sm px-3">Live Stats</span>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-7">
                            <div class="chart-container" >
                                <canvas id="studentBar"></canvas>
                            </div>
                        </div>
                        <div class="col-5 border-start" >
                            <div class="chart-container" >
                                <canvas id="studentPie"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="card apple-card p-4 h-100 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold m-0"><i class="fas fa-chalkboard-teacher me-2 text-success"></i> Teacher
                            Analytics</h5>
                        <span class="badge bg-light text-dark rounded-pill shadow-sm px-3">Live Stats</span>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-7">
                            <div class="chart-container" >
                                <canvas id="teacherBar"></canvas>
                            </div>
                        </div>
                        <div class="col-5 border-start">
                            <div class="chart-container">
                                <canvas id="teacherPie"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
