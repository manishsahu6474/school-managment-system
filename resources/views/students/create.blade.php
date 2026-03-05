

<x-app-layout>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card card-morphism border-0 shadow-lg">
                <div class="card-header morphism-header text-white">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-user-plus me-2"></i> Add Student
                    </h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.students.store') }}" method="POST">
                        @csrf
                        @include('students.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>