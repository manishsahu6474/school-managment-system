
<x-app-layout>
        <div class="container-fluid px-md-5">
            <div
                class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-4 mt-3 position-relative gap-3">

                <div class="d-none d-md-block" style="width: 180px;"></div>

                <div class="text-center flex-grow-1">
                    <h2 class="fw-bold text-dark text-uppercase  mb-0 display-6 main-heading">
                        <i class="fas fa-users text-primary me-2"></i>
                        Students List
                    </h2>
                </div>

                <div class="text-center text-md-end" style="min-width: 180px;">
                    <a href="{{ route('admin.students.create') }}"
                        class="btn-3d-success shadow-sm w-100  ">
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
                            <div class="morpihsm-input">
                                <span class=" bg-white border-end-0 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0"
                                    placeholder="Search ..." value="{{ $search ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-5 d-flex gap-2">
                            <button class="btn  btn-3d-primary w-100 fw-bold" type="submit">SEARCH</button>
                            <a href="{{ route('admin.students.index') }}"
                                class="btn btn-secondary btn-3d-secondary w-100 fw-bold">RESET</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Table --}}
        <div class="card  card-3d ">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle ">
                    <thead class="table-light ">
                        <tr class="text-uppercase" style="font-size: .85rem; letter-spacing: 0.5px;">

                            <th class="text-center">S.No.</th>
                            <th>Student Name</th>
                            <th>Father Name</th>
                            <th class="text-center">Roll NO.</th>
                            <th class="text-center">DOB</th>
                            <th>Phone No.</th>
                            <th class="text-center">Class</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                {{-- S.No ke liye Pagination index use karein taaki har page pe 1 se shuru na ho --}}
                                <td class="text-center">
                                    {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>

                                {{-- User table se Name aur Email fetch karna --}}
                                <td class="fw-bold">{{ $student->user->name ?? 'N/A' }}</td>
                                <td>{{ $student->father_name ?? 'N/A' }}</td>
                                <td>{{ $student->roll_no ?? 'N/A' }}</td>

                                {{-- DOB check: Agar DOB null hai toh 'N/A' dikhayein --}}
                                <td>{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d-M-Y') : 'Not Set' }}
                                </td>
                                <td>{{ $student->phone ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill bg-info text-dark">{{ $student->class }}<sup>th</sup></span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.students.status', $student->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="button" data-id="{{ $student->id }}"
                                            onclick="confirmStatusChange(this)"
                                            class="btn btn-sm {{ $student->status == 1 ? 'btn-success' : 'btn-secondary' }}">
                                            {{ $student->status == 1 ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
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
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 px-3">
    
    <div class="pagination-info small text-muted mb-2 mb-md-0">
        Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students
    </div>

    <div class="pagination-3d">
        {{ $students->appends(['search' => $search])->links() }}
    </div>

</div>
    </div>
</x-app-layout>
