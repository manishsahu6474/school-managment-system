<x-app-layout>
    <div class="container-fluid px-md-5">
        <div
            class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-4 mt-3 position-relative gap-3">
            <div class="d-none d-md-block" style="width: 180px;"></div>
            <div class="text-center flex-grow-1">
                <h2 class="fs-5 fs-md-4  fw-bold text-dark text-uppercase  mb-0 ">
                    <i class="fas fa-users text-primary me-2"></i>
                    Students List
                </h2>
            </div>

            <div class="text-center text-md-end" style="min-width: 180px;">
                <a href="{{ route('admin.students.create') }}"
                    class=" btn btn-success btn-3d-success shadow-sm w-100 fw-bold px-4">
                    <i class="fas fa-plus-circle me-2"></i> Add Student
                </a>
            </div>
        </div>
        {{-- Search --}}
        <div class="row justify-content-center mb-5">
            <div class="col-md-10 col-lg-8">
                <div class="card card-3d border-0 p-4">
                    <form method="GET" action="{{ route('admin.students.index') }}" class="row g-3">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <div class="col-md-7">
                            <div class="morpihsm-input">
                                <input type="text" name="search" class="form-control text-truncate border-start-0 "
                                    placeholder="Search Name, Roll No & Class(e.g.9th)..." value="{{ $search ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-5 d-flex gap-2">
                            <button class="btn  btn-3d-primary w-100 fw-bold btn-sm" type="submit">SEARCH</button>
                            <a href="{{ route('admin.students.index', ['status' => request('status')]) }}"
                                class="btn btn-secondary btn-3d-secondary w-100 fw-bold btn-sm">RESET</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Status Tabs --}}
        <div class="d-flex  align-items-center gap-2 gap-sm-4 mb-4">

            <a href="{{ route('admin.students.index', ['status' => 'active', 'search' => $search]) }}"
                class="btn btn-3d-primary btn-sm  flex-shrink-0">
                <i class="fas fa-check-circle me-1"></i>
                <span class="d-sm-inline" style="font-size: 13px;">Active</span>
            </a>

            <a href="{{ route('admin.students.index', ['status' => 'pending', 'search' => $search]) }}"
                class="btn btn-3d-warning btn-sm  position-relative flex-shrink-0">
                <i class="fas fa-clock me-1"></i>
                <span class="d-sm-inline" style="font-size: 13px;">Pending @if (($pendingCount ?? 0) > 0)
                        ({{ $pendingCount ?? 0 }})
                    @endif
                </span>

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
        {{-- Bulk Actions Wrapper --}}
        <div id="bulk-actions-wrapper" class="mb-3 animate__animated animate__fadeIn" style="display: none;">
            <div class="card card-3d border-0 p-2 shadow-sm" style="background: rgba(255, 255, 255, 0.8);">
                <div class="d-flex align-items-center justify-content-between px-3">
                    <span class="fw-bold text-primary small">
                        <i class="fas fa-tasks me-2"></i> Bulk Actions
                    </span>
                    <div class="d-flex gap-2">
                        @if (request('status') == 'pending')
                            <button onclick="bulkApprove()" class="btn-3d-success btn-sm py-1">
                                <i class="fas fa-check-double me-1"></i> Approve Selected
                            </button>
                            <button onclick="bulkStudentDelete(true)" class="btn-3d-danger btn-sm py-1">
                                <i class="fas fa-trash-alt me-1"></i> Delete Selected
                            </button>
                        @elseif(request('status') == 'inactive')
                            <button onclick="bulkActivate()" class="btn-3d-primary btn-sm py-1">
                                <i class="fas fa-user-plus me-1"></i> Re-Activate Selected
                            </button>
                        @else
                            <button onclick="bulkPromote()" class="btn-3d-primary btn-sm py-1">
                                <i class="fas fa-graduation-cap me-1"></i> Promote Selected
                            </button>
                            <button onclick="bulkStudentDelete(false)" class="btn-3d-warning btn-sm py-1">
                                <i class="fas fa-user-slash me-1"></i> Inactivate Selected
                            </button>
                        @endif

                    </div>
                </div>
            </div>
        </div>
        {{-- Table --}}
        <div class="card  card-3d ">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle ">
                    <thead class="table-light ">
                        <tr class="text-uppercase" style="font-size: .85rem; letter-spacing: 0.5px;">
                            <th class="text-center">
                                <input type="checkbox" id="master-checkbox"
                                    class="form-check-input border-primary shadow-sm">
                            </th>
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
                                <td class="text-center">
                                    <input type="checkbox"
                                        class="form-check-input record-checkbox student-checkbox border-primary shadow-sm"
                                        value="{{ $student->id }}">
                                </td>
                                <td class="text-center">
                                    {{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>

                                <td class="fw-bold">{{ $student->user->name ?? 'N/A' }}</td>
                                <td>{{ $student->father_name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge bg-light text-dark border">{{ $student->roll_no ?? 'N/A' }}</span>
                                </td>
                                <td>{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('d-M-Y') : 'Not Set' }}
                                </td>
                                <td>{{ $student->phone ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill bg-info text-dark">{{ $student->classes->class_name }}<sup>th</sup></span>
                                </td>
                                <td data-label="Status" class="text-center">
                                    @if ($student->status == 2)
                                        <form action="{{ route('admin.students.status', $student->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="button" onclick="activateStudent(this)"
                                                class="btn btn-sm rounded-pill px-3 status-toggle-btn btn-secondary shadow-sm"
                                                style="cursor: pointer;" title="Activated Student">
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
                                                <button type="button" onclick="approveStudent(this)"
                                                    class="action-btn btn-approve shadow-sm"
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
                                                method="POST" id="delete-form-{{ $student->id }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="action-btn btn-3d-danger shadow-sm"
                                                    onclick="deleteStudent({{ $student->id }}, {{ $student->status }},this)"
                                                    title="{{ $student->status == 1 ? 'Make Inactive' : 'Delete Student' }}">
                                                    @if ($student->status == 1)
                                                        <i class="fas fa-minus"></i>
                                                    @elseif ($student->status == 0)
                                                        <i class="fas fa-trash-alt"></i>
                                                    @endif
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                @if (isset($search) && $search != '')
                                    {{-- Search Results Case --}}
                                    <td colspan="10" class="text-center text-muted py-5">
                                        <i class="fas fa-search mb-3 fa-3x opacity-50"></i><br>
                                        <h5 class="fw-bold text-dark">No Results Found</h5>
                                        <p>We couldn't find any matches for "<strong>{{ $search }}</strong>"</p>
                                    </td>
                                @elseif (request('status') == 'pending')
                                    {{-- Pending Students Case --}}
                                    <td colspan="10" class="text-center text-muted py-5">
                                        <i class="fas fa-user-clock mb-3 fa-3x opacity-50 text-info"></i><br>
                                        <h5 class="fw-bold text-dark">All Caught Up!</h5>
                                        <p>There are no <strong>Pending</strong> student registrations at the moment.
                                        </p>
                                    </td>
                                @elseif (request('status') == 'inactive')
                                    {{-- Inactive Students Case --}}
                                    <td colspan="10" class="text-center text-muted py-5">
                                        <i class="fas fa-user-shield mb-3 fa-3x opacity-50 text-secondary"></i><br>
                                        <h5 class="fw-bold text-dark">Archive is Empty</h5>
                                        <p>No student records have been marked as <strong>Inactive</strong> yet.</p>
                                    </td>
                                @else
                                    {{-- Default/Active Tab Empty Case --}}
                                    <td colspan="10" class="text-center text-muted py-5">
                                        <i class="fas fa-users-slash mb-3 fa-3x opacity-50 text-warning"></i><br>
                                        <h5 class="fw-bold text-dark">No Students Found</h5>
                                        <p>The active student list is currently empty.</p>
                                    </td>
                                @endif
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
                {{ $students->appends(['search' => $search, 'status' => request('status')])->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
