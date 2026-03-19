
<x-app-layout>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card card-morphism border-0 shadow-lg">
                <div class="card-header morphism-header text-white" style="background: linear-gradient(135deg, #1277f3, #0fcff1) !important;">
                    <h6 class="mb-0 fw-bold text-uppercase">
                        <i class="fas fa-edit me-2"></i> Edit Student
                    </h6>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('students.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>