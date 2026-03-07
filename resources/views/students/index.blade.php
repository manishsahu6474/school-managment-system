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
                <a href="{{ route('admin.students.create') }}" class="btn-3d-success shadow-sm w-100  ">
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
        {{-- Status Tabs --}}
        <div class="d-flex flex-wrap align-items-center gap-2 gap-sm-4 mb-4">

            <a href="{{ route('admin.students.index', ['status' => 'active', 'search' => $search]) }}"
                class="btn btn-3d-primary btn-sm  flex-shrink-0">
                <i class="fas fa-check-circle me-1"></i>
                <span class="d-sm-inline" style="font-size: 13px;">Active</span>
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'pending', 'search' => $search]) }}"
                class="btn btn-3d-warning btn-sm  position-relative flex-shrink-0">
                <i class="fas fa-clock me-1"></i>
                <span class="d-sm-inline" style="font-size: 13px;">Pending ({{ $pendingCount ?? 0 }})</span>

                @if (($pendingCount ?? 0) > 0)
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm mobile-badge-adj"
                        style=" font-size: 7px; padding: 2px 4px;">
                        NEW
                    </span>
                @endif
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'inactive', 'search' => $search]) }}"
                class="btn btn-3d-secondary btn-sm  flex-shrink-0">
                <i class="fas fa-ban me-1"></i>
                <span class="d-sm-inline" style="font-size: 13px;">Inactive</span>
            </a>

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
                                <td data-label="Status" class="text-center">
                                    @if ($student->status == 2)
                                        <form action="{{ route('admin.students.status', $student->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="button" onclick="activateStudent(this)"
                                                class="btn btn-sm rounded-pill px-3 status-toggle-btn btn-secondary shadow-sm"
                                                style="cursor: pointer;">
                                                Inactive
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="btn btn-sm rounded-pill px-3 {{ $student->status == 1 ? 'btn-success' : 'btn-warning' }}"
                                            style="cursor: default; opacity: 0.9;">
                                            {{ $student->status == 1 ? 'Active' : 'Pending' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        {{-- 1. Approve Button (Icon Only for Balance) --}}
                                        @if ($student->status == 0)
                                            <form action="{{ route('admin.students.approve', $student->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="action-btn btn-approve shadow-sm"
                                                    title="Approve Admission">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- 2. Edit Button --}}
                                        <a href="{{ route('admin.students.edit', $student->id) }}"
                                            class="action-btn btn-3d-warning shadow-sm " title="Edit Student">
                                            <i class="fas fa-edit "></i>
                                        </a>

                                        {{-- 3. Delete Button --}}
                                        @if ($student->status == 1 || $student->status == 0)
                                            <form action="{{ route('admin.students.destroy', $student->id) }}"
                                                method="POST" id="delete-form-{{ $student->id }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="action-btn btn-3d-danger shadow-sm"
                                                    onclick="deleteStudent({{ $student->id }}, {{ $student->status }})"
                                                    title="{{ $student->status == 1 ? 'Make Inactive' : 'Delete Student' }}">
                                                    @if ($student->status == 1)
                                                        <i class="fas fa-minus"></i>
                                                    @elseif ($student->status == 0)
                                                        <i class="fas fa-trash"></i>
                                                    @endif
                                                </button>
                                            </form>
                                        @endif
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
                Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }}
                students
            </div>

            <div class="pagination-3d">
                {{ $students->appends(['search' => $search])->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
