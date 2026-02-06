@extends('layouts.app') {{-- Yahan apne master layout ka path check kar lein --}}

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-3d">
                <div class="card-header card-header-3d text-center text-white">
                    <h3 class="mb-0">EDIT STUDENT</h3>
                </div>
                <div class="card-body p-4">
                    
                    <form action="{{ route('students.update', $student->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="fw-bold mb-1">Name</label>
                                <input type="text" name="name" class="form-control-3d w-100" value="{{ old('name', $student->name) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="fw-bold mb-1">Email</label>
                                <input type="email" name="email" class="form-control-3d w-100" value="{{ old('email', $student->email) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="fw-bold mb-1">Phone</label>
                                <input type="text" name="phone" class="form-control-3d w-100" value="{{ old('phone', $student->phone) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="fw-bold mb-1">Class</label>
                                <input type="text" name="class" class="form-control-3d w-100" value="{{ old('class', $student->class) }}" required>
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold mb-1">Date of Birth</label>
                                <input type="date" name="dob" class="form-control-3d w-100" value="{{ old('dob', $student->dob) }}" required>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-3d-success px-5 me-2">Update Data</button>
                            <a href="{{ route('students.index') }}" class="btn btn-3d-secondary px-5">Go Back</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection