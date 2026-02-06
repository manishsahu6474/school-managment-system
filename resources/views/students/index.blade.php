@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html>
<head>
    
</head>

<body class="bg-light">
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3 ">
        <h3><i class="bi bi-people-fill"></i> Student List</h3>
        <a href="{{ route('students.create') }}" class="btn btn-success btn-3d">
            <i class="bi bi-plus-circle"></i> Add Student
        </a>
    </div>

    {{-- Search --}}
    <form method="GET" action="{{ route('students.index') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Search name / email / class"
                   value="{{ $search ?? '' }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary btn-3d w-100">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('students.index') }}" class="btn btn-secondary btn-3d w-100">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </a>
        </div>
    </form>

    {{-- SweetAlert Success --}}
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    @endif

    {{-- Table --}}
    <div class="card shadow-sm card-header text-center">
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-dark">
                <tr class="text-center">
                    <th>S.No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>DOB</th>
                    <th>Class</th>
                    <th width="160">Action</th>
                </tr>
                </thead>

                <tbody>
                @forelse($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ \Carbon\Carbon::parse($student->dob)->format('d-m-Y') }}</td>
                        <td>{{ $student->class }}</td>
                        <td>
                            <a href="{{ route('students.edit',$student->id) }}"
                               class="btn btn-warning btn-sm btn-3d">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('students.destroy',$student->id) }}"
                                  method="POST"
                                  class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm btn-3d">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No students found
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $students->appends(['search' => $search])->links() }}
    </div>

</div>
</body>
</html>
@endsection