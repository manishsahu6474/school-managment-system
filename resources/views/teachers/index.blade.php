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
                        <div class="col-md-7">
                            <div class="morpihsm-input">
                                <span class="bg-white border-end-0 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0"
                                    placeholder="Search..." value="{{ $search ?? '' }}">
                            </div>
                        </div>
                        <div class="col-md-5 d-flex gap-2">
                            <button class="btn   btn-3d-primary w-100 fw-bold" type="submit">SEARCH</button>
                            <a href="{{ route('admin.teachers.index') }}"
                                class="btn btn-secondary btn-3d-secondary w-100 fw-bold">RESET</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        {{-- Table --}}
        <div class="card  card-3d ">
            <div class="card-body table-responsive">
                <table class="table ">
                    <thead class="text-center ">
                        <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th width="160">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $k => $teacher)
                            <tr class="text-center">
                                <td>{{ $k + 1 }}</td>
                                <td>{{ $teacher->name }}</td>
                                <td>{{ $teacher->email }}</td>
                                <td>
                                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}"
                                        class="btn btn-sm btn-3d-warning shadow-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form
                                        id="delete-form-{{ $teacher->id }}"action="{{ route('admin.teachers.destroy', $teacher->id) }}"
                                        method="POST" class="d-inline delete-form" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-3d-danger"
                                            onclick="deleteTeacher({{ $teacher->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
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
                Showing {{ $teachers->firstItem() }} to {{ $teachers->lastItem() }} of {{ $teachers->total() }}
                teachers
            </div>
            <div class="pagination-3d">
                {{ $teachers->appends(['search' => $search])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
