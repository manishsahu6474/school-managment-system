<x-app-layout>
    <div id="page-context" data-type="class-view" class="container-fluid px-md-5">
        <div class="container-fluid px-md-5">
            <div
                class="d-flex flex-column flex-md-row align-items-center justify-content-between mb-4 mt-3 position-relative gap-3">
                <div class="d-none d-md-block" style="width: 180px;"></div>
                <div class="text-center flex-grow-1">
                    <h2 class="fw-bold text-dark text-upper mb-0 display-6 main-heading">
                        <i class="fas fa-book text-primary me-2"></i>
                        Subjects
                    </h2>
                </div>
                <div class="text-center text-md-end" style="min-width: 180px;">
                    <button type="button" class="btn-3d-success shadow-sm px-4" data-bs-toggle="modal"
                        data-bs-target="#addSubjectModal">
                        <i class="fas fa-plus-circle me-2"></i> Add Subject
                    </button>
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
                            <th>Subject Name</th>
                            <th class="text-center">Subject Teacher Name</th>
                            <th class="text-center">Class Name</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subjects as $subject)
                            <tr>
                                <td class="text-center">
                                    {{ ($subjects->currentPage() - 1) * $subjects->perPage() + $loop->iteration }}</td>
                                <td class="fw-bold">{{ $subject->subject_name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    @forelse($subject->classes as $class)
                                        <span class="text-primary">
                                            @php $t = \App\Models\Teacher::with('user')->find($class->pivot->teacher_id); @endphp
                                            {{ $t->user->name ?? 'N/A' }}
                                        </span>
                                    @empty
                                        <span class="text-muted small">Not assigned</span>
                                    @endforelse
                                </td>
                                <td class="text-center">
                                    @forelse($subject->classes as $class)
                                        <span class="badge bg-primar text-primary border border-info-subtle mb-1">
                                            {{ $class->class_name }}<sup>th</sup>
                                        </span>
                                    @empty
                                        <span class="text-muted small">Not assigned </span>
                                    @endforelse
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <form action="{{ route('admin.subjects.destroy', $subject->id) }}"
                                            method="POST" id="delete-form-{{ $subject->id }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="action-btn btn-3d-danger shadow-sm"
                                                onclick="deletesubject({{ $subject->id }},this)"
                                                title="Delete subject">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 px-3">

            <div class="pagination-info small text-muted mb-2 mb-md-0">
                Showing {{ $subjects->firstItem() }} to {{ $subjects->lastItem() }} of {{ $subjects->total() }}
                subjects
            </div>
        </div>
    </div>
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg card-body">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i>Add New Subject</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="addSubjectForm" action="{{ route('admin.subjects.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">Subject Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="subject_name" class="form-control morphism-input"
                                placeholder="Enter Subject Name (e.g. Mathematics)" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-2">
                        <button type="button" class=" btn-3d-secondary px-4" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class=" btn-3d-primary px-4 shadow">Save Subject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
