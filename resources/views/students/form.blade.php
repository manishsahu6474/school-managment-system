<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Student Name</label>
        <input type="text" name="name" 
               class="form-control morphism-input @error('name') is-invalid @enderror" 
               placeholder="Enter full name" 
               value="{{ old('name', $student->name ?? '') }}" 
               required> </div>

    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Email Address</label>
        <input type="email" name="email" 
               class="form-control morphism-input @error('email') is-invalid @enderror" 
               placeholder="Enter email" 
               value="{{ old('email', $student->email ?? '') }}" 
               required> </div>

    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Date of Birth</label>
        <input type="date" name="dob" 
               class="form-control morphism-input @error('dob') is-invalid @enderror" 
               value="{{ old('dob', $student->dob ?? '') }}" 
               required>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Class</label>
        <input type="text" name="class" 
               class="form-control morphism-input @error('class') is-invalid @enderror" 
               placeholder="e.g. 10th, 12th" 
               value="{{ old('class', $student->class ?? '') }}" 
               required>
    </div>

    <div class="col-md-12">
        <label class="form-label fw-bold text-muted ml-1">Phone Number</label>
        <input type="text" name="phone" 
               class="form-control morphism-input @error('phone') is-invalid @enderror" 
               placeholder="10 digit number" 
               value="{{ old('phone', $student->phone ?? '') }}" 
               required 
               pattern="[0-9]{10}"> </div>
</div>

<div class="d-flex justify-content-center gap-3 mt-5">
    <button type="submit" class="btn btn-3d-success px-5 py-2 fw-bold">
        <i class="fas fa-check-circle me-1"></i> {{ isset($student) ? 'Update Data' : 'Save Student' }}
    </button>
    <a href="{{ route('students.index') }}" class="btn btn-3d-secondary px-5 py-2 fw-bold">
        <i class="fas fa-arrow-left me-1"></i> Go Back
    </a>
</div>