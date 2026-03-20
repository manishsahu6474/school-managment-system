<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row g-4">
            @foreach ($classdata as $class)
                <div class="col-xl-6 col-md-6">
                    <a href="{{route('admin.classes.students',['classes'=>$class->id]) }}"
                         class="{{ $class->students_count == 0 ? 'link-disabled' : '' }} " style="text-decoration: none;">
                        <div class="card card-3d border-0 shadow-sm h-100"
                            style="background: linear-gradient(145deg, #ffffff, #f0f0f0);">
                            <div class="card-body text-center p-4">
                                <div class="icon-box mb-3 mx-auto shadow-sm"
                                    style="background: #e7f1ff; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-school fa-2x text-primary"></i>
                                </div>
                                <h6 class="text-uppercase fw-bold text-muted mb-1">Class
                                    {{ $class->class_name }}<sup>th</sup></h6>
                                <h6 class="text-uppercase fw-bold text-muted mb-1">Total Students</h6>
                                <h6 class="fw-bold text-dark mb-0">{{ $class->students_count }}</h6>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
