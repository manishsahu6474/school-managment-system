<x-app-layout>
<div class="container-fluid px-md-5">
    <div class="row mb-3 mt-2 align-items-center">
       <div class="col-md-6 text-start ">
            <h2 class="fw-bold text-dark mb-0 d-inline-block p-2" style="margin-left: 10px;">
                <i class="fas fa-user-graduate text-primary me-2"></i> Student List
            </h2>
       </div>

        <div class="col-md-6 text-end">
            <a href="{{ route('students.create') }}" 
               class="btn btn-success btn-3d-success shadow-sm px-4" style="margin-right: 30px;">
                <i class="fas fa-plus-circle me-2"></i> Add Student
            </a>
        </div>
    </div>
    {{-- Search --}}
       <div class="row justify-content-center mb-5">
           <div class="col-md-10 col-lg-8">
             <div class="card card-3d border-0 p-4">
                 <form method="GET" action="{{ route('students.index') }}" class="row g-3">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0" 
                             placeholder="Search by name, email or class..." value="{{ $search ?? '' }}">
                        </div>
                    </div>
                     <div class="col-md-5 d-flex gap-2">
                            <button class="btn   btn-3d-primary w-100 fw-bold" type="submit">SEARCH</button>
                            <a href="{{ route('students.index') }}" 
                               class="btn btn-secondary btn-3d-secondary w-100 fw-bold">RESET</a>
                    </div>
                </form>
             </div>
           </div>
       </div>
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
     <div class="card  card-3d ">
         <div class="card-body table-responsive">
               <table class="table ">
                    <thead class="text-center ">
                        <tr >
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>DOB</th>
                            <th>Phone No.</th>
                            <th>Class</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr class="text-center">
                                <td>{{ $student->id }}</td>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ \Carbon\Carbon::parse($student->dob)->format('d-m-Y') }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>{{ $student->class }}</td>
                                <td>
                                    <a href="{{ route('students.edit',$student->id) }}"
                                     class="btn btn-sm btn-3d-warning shadow-sm">
                                     <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                       <button type="button" class="btn btn-sm btn-3d-danger" onclick="deleteStudent({{ $student->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                         @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No students found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
             </table>
          </div>
     </div>
    {{-- Pagination --}}
     <div class="d-flex justify-content-between align-items-center mt-3 px-3">
            <div class="small text-muted">
                Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students
            </div>
            <div class="pagination-3d">
                {{ $students->appends(['search' => $search])->links() }}
            </div>
    </div>  
</div>
</x-app-layout>