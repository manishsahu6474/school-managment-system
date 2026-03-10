<x-app-layout>
    <div class="container-fluid px-md-5">
        <div class="container-fluid px-md-5">
            <div
                class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-4 mt-3 position-relative gap-3">
                <div class="d-none d-md-block" style="width: 180px;"></div>
                <div class="text-center flex-grow-1">
                    <h2 class="fw-bold text-dark text-uppercase
                     mb-0 display-6 main-heading">
                        <i class="fas fa-chalkboard-teacher  text-primary me-2"></i>
                        Teachers List
                    </h2>
                </div>
                <div class="text-center text-md-end" style="min-width: 180px;">
                    <a href="{{ route('admin.teachers.create') }}"
                        class="btn btn-success btn-3d-success shadow-sm w-100 fw-bold px-4">
                        <i class="fas fa-plus-circle me-2"></i> Add Teacher
                    </a>
                </div>
            </div>
        </div>
        {{-- Search --}}
        <div class="row justify-content-center mb-5">
            <div class="col-md-10 col-lg-8">
                <div class="card card-3d border-0 p-4">
                    <form method="GET" action="{{ route('admin.teachers.index') }}" class="row g-3">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <div class="col-md-7">
                            <div class="morpihsm-input">
                                <input type="text" name="search" class="form-control border-start-0"
                                    placeholder="Search by Name,Subject..." value="{{ $search ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-5 d-flex gap-2">
                            <button class="btn  btn-3d-primary w-100 fw-bold" type="submit">SEARCH</button>
                            <a href="{{ route('admin.teachers.index', ['status' => request('status')]) }}"
                                class="btn btn-secondary btn-3d-secondary w-100 fw-bold">RESET</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Status Tabs --}}
        <div class="d-flex flex-wrap align-items-center gap-2 gap-sm-4 mb-4">

            <a href="{{ route('admin.teachers.index', ['status' => 'active', 'search' => $search]) }}"
                class="btn btn-3d-primary btn-sm  flex-shrink-0">
                <i class="fas fa-check-circle me-1"></i>
                <span class="d-sm-inline" style="font-size: 13px;">Active</span>
            </a>

            <a href="{{ route('admin.teachers.index', ['status' => 'pending', 'search' => $search]) }}"
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

            <a href="{{ route('admin.teachers.index', ['status' => 'inactive', 'search' => $search]) }}"
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
                            <button onclick="bulkTeacherApprove()" class="btn-3d-success btn-sm py-1">
                                <i class="fas fa-check-double me-1"></i> Approve Selected
                            </button>
                            <button onclick="bulkTeacherDelete(true)" class="btn-3d-danger btn-sm py-1">
                                <i class="fas fa-trash-alt me-1"></i> Delete Selected
                            </button>
                        @elseif(request('status') == 'inactive')
                            <button onclick="bulkTeacherActivate()" class="btn-3d-primary btn-sm py-1">
                                <i class="fas fa-user-plus me-1"></i> Re-Activate Selected
                            </button>
                        @else
                            <button onclick="bulkTeacherDelete(false)" class="btn-3d-warning btn-sm py-1">
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
                            <th>Teacher Details</th>
                            <th class="text-center">Subject</th>
                            <th class="text-center">Qualification</th>
                            <th class="text-end">Salary</th>
                            <th class="text-center">Joined</th>
                            <th>Phone</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox"
                                        class="form-check-input record-checkbox teacher-checkbox border-primary shadow-sm"
                                        value="{{ $teacher->id }}">
                                </td>
                                <td class="text-center">
                                    {{ ($teachers->currentPage() - 1) * $teachers->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $teacher->user->name }}</div>
                                    <small class="text-muted text-capitalize">{{ $teacher->gender }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        {{ $teacher->subject->subject_name ?? 'N/A'}}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $teacher->qualification }}</span>
                                </td>
                                <td class="text-end fw-bold text-success">
                                    ₹ {{ number_format($teacher->salary, 2) }}
                                </td>
                                <td class="text-center">
                                    <div class="small">
                                        {{ \Carbon\Carbon::parse($teacher->joining_date)->format('d M') }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($teacher->joining_date)->format('Y') }}</div>
                                </td>
                                </td>
                                <td class="text-nowrap">{{ $teacher->phone }}</td>
                                <td data-label="Status" class="text-center">
                                    @if ($teacher->status == 2)
                                        <form action="{{ route('admin.teachers.status', $teacher->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="button" onclick="activateteacher(this)"
                                                class="btn btn-sm rounded-pill px-3 status-toggle-btn btn-secondary shadow-sm"
                                                style="cursor: pointer;" title="Activated teacher">
                                                Inactive
                                            </button>
                                        </form>
                                    @else
                                        <span
                                            class="btn btn-sm rounded-pill px-3 {{ $teacher->status == 1 ? 'btn-success' : 'btn-warning' }}"
                                            style="cursor: default; opacity: 0.9;">
                                            {{ $teacher->status == 1 ? 'Active' : 'Pending' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">

                                        {{-- 1. Approve Button (Icon Only for Balance) --}}
                                        @if ($teacher->status == 0)
                                            <form action="{{ route('admin.teachers.approve', $teacher->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="button" onclick="approveteacher(this)"
                                                    class="action-btn btn-approve shadow-sm"
                                                    title="Approve Admission">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- 2. Edit Button --}}
                                        <a href="{{ route('admin.teachers.edit', $teacher->id) }}"
                                            class="action-btn btn-3d-warning shadow-sm " title="Edit teacher">
                                            <i class="fas fa-edit "></i>
                                        </a>

                                        {{-- 3. Delete Button --}}
                                        @if ($teacher->status == 1 || $teacher->status == 0)
                                            <form action="{{ route('admin.teachers.destroy', $teacher->id) }}"
                                                method="POST" id="delete-form-{{ $teacher->id }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="action-btn btn-3d-danger shadow-sm"
                                                    onclick="deleteTeacher({{ $teacher->id }}, {{ $teacher->status }},this)"
                                                    title="{{ $teacher->status == 1 ? 'Make Inactive' : 'Delete teacher' }}">
                                                    @if ($teacher->status == 1)
                                                        <i class="fas fa-minus"></i>
                                                    @elseif ($teacher->status == 0)
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
                                <td colspan="11" class="text-center text-muted">
                                    No teachers found
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
                Showing {{ $teachers->firstItem() }} to {{ $teachers->lastItem() }} of {{ $teachers->total() }}
                teachers
            </div>
            <div class="pagination-3d">
                {{ $teachers->appends(['search' => $search])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
