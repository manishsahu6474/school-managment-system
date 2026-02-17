<x-app-layout>
    <div class="container-fluid px-md-5">
        <div class="row mb-3 mt-2 align-items-center">
            <div class="col-md-6 text-start ">
                <h2 class="fw-bold text-dark mb-0 d-inline-block p-2" style="margin-left: 10px;">
                    <i class="fas fa-user-graduate text-primary me-2"></i> Student List
                </h2>
            </div>

            <div class="col-md-6 text-end">
                <a href="{{ route('admin.students.create') }}" class="btn btn-success btn-3d-success shadow-sm px-4"
                    style="margin-right: 30px;">
                    <i class="fas fa-plus-circle me-2"></i> Add Student
                </a>
            </div>
        </div>
        {{-- Search --}}
        <div class="row justify-content-center mb-5">
            <div class="col-md-10 col-lg-8">
                <div class="card card-3d border-0 p-4">
                    <form method="GET" action="{{ route('admin.students.index') }}" class="row g-3">
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
                            <button type="reset"
                                class="btn btn-secondary btn-3d-secondary w-100 fw-bold">RESET</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function() {

                // 1. Check karein ki user kaise aaya hai (Reload, Link, ya Back Button)
                // Modern browsers ke liye logic
                var perfEntries = performance.getEntriesByType("navigation");
                var navigationType = perfEntries.length > 0 ? perfEntries[0].type : "navigate";

                // 2. Agar ye "Back Button" (back_forward) nahi hai, tabhi alert dikhao
                if (navigationType !== 'back_forward') {

                    @if (session('success'))
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: "{{ session('success') }}",
                            showConfirmButton: false,
                            timer: 2000 // 2 second baad khud band ho jayega
                        });
                    @endif

                    @if (session('error'))
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: "{{ session('error') }}",
                        });
                    @endif
                }
            });
        </script>
        {{-- Table --}}
        <div class="card  card-3d ">
            <div class="card-body table-responsive">
                <table class="table ">
                    <thead class="text-center ">
                        <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Father Name</th>
                            <th>Roll NO.</th>
                            <th>DOB</th>
                            <th>Phone No.</th>
                            <th>Class</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr class="text-center">
                                {{-- S.No ke liye Pagination index use karein taaki har page pe 1 se shuru na ho --}}
                                <td>{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>

                                {{-- User table se Name aur Email fetch karna --}}
                                <td class="fw-bold">{{ $student->user->name ?? 'N/A' }}</td>
                                <td>{{ $student->user->email ?? 'N/A' }}</td>
                                <td>{{ $student->father_name ?? 'N/A' }}</td>
                                <td>{{ $student->roll_no ?? 'N/A' }}</td>

                                {{-- DOB check: Agar DOB null hai toh 'N/A' dikhayein --}}
                                <td>{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d-m-Y') : 'Not Set' }}
                                </td>

                                <td>{{ $student->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">{{ $student->class }}</span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.students.edit', $student->id) }}"
                                            class="btn btn-sm btn-3d-warning shadow-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Delete form logic fix --}}
                                        <form action="{{ route('admin.students.destroy', $student->id) }}"
                                            method="POST" id="delete-form-{{ $student->id }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-3d-danger"
                                                onclick="deleteStudent({{ $student->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="fas fa-search mb-2 fa-2x"></i><br>
                                    No students found for "{{ $search }}"
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
                Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }}
                students
            </div>
            <div class="pagination-3d">
                {{ $students->appends(['search' => $search])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
