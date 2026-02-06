@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10"> {{-- Image ke hisaab se thoda wide rakha hai --}}
            <div class="card card-3d">
                <div class="card-header card-header-3d">
                    <h5 class="text-white mb-0">
                        <i class="fas fa-user-plus me-2"></i> Add Student
                    </h5>
                </div>
                <div class="card-body p-4">
                    
                    <form action="{{ route('students.store') }}" method="POST">
                        @csrf

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="fw-bold mb-1 text-secondary">Student Name</label>
                                <input type="text" name="name" class="form-control-3d w-100" placeholder="Enter full name" value="{{ old('name') }}" required>
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="fw-bold mb-1 text-secondary">Email Address</label>
                                <input type="email" name="email" class="form-control-3d w-100" placeholder="Enter email" value="{{ old('email') }}" required>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="fw-bold mb-1 text-secondary">Date of Birth</label>
                                <input type="date" name="dob" class="form-control-3d w-100" value="{{ old('dob') }}" required>
                                @error('dob') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="fw-bold mb-1 text-secondary">Class</label>
                                <input type="text" name="class" class="form-control-3d w-100" placeholder="e.g. 10th, 12th" value="{{ old('class') }}" required>
                                @error('class') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="fw-bold mb-1 text-secondary">Phone</label>
                                <input type="text" name="phone" class="form-control-3d w-100" placeholder="10 digit number" value="{{ old('phone') }}" required>
                                @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <a href="{{ route('students.index') }}" class="btn btn-3d-secondary px-4">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                            <button type="submit" class="btn btn-3d-success px-5">
                                <i class="fas fa-check-circle me-1"></i> Save Student
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection