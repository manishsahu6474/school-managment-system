
<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Teacher Name</label>
        <input type="text" name="name" 
               class="form-control morphism-input @error('name') is-invalid @enderror" 
               placeholder="Enter full name" 
               value="{{ old('name', $teacher->name ?? '') }}" 
               required> </div>

    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Email Address</label>
        <input type="email" name="email" 
               class="form-control morphism-input @error('email') is-invalid @enderror" 
               placeholder="Enter email" 
               value="{{ old('email', $teacher->email ?? '') }}" 
               required> </div>
        <div class="mb-6">
            <label class="form-label fw-bold text-muted ml-1">Password </label>
            <input type="password" name="password" 
                   class="form-control morphism-input @error('password') is-invalid @enderror" 
                   placeholder="Create a strong password">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
<div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-3 gap-sm-3 mt-4 mt-sm-5">
    <button type="submit" class="btn btn-success btn-3d-success px-5 py-2 fw-bold">
        <i class="fas fa-check-circle me-1"></i> {{ isset($teacher) ? 'Update Data' : 'Save Teacher' }}
    </button>
    <a href="{{ route('admin.teachers.index') }}" class="btn btn-3d-secondary px-5 py-2 fw-bold">
        <i class="fas fa-arrow-left me-1"></i> Go Back
    </a>
</div>
