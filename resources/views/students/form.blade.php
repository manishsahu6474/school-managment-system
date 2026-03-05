<div class="row g-4 text-start">
    {{-- Student Name (User Table se) --}}
    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Student Name</label>
        <input type="text" name="name" 
               class="form-control morphism-input @error('name') is-invalid @enderror" 
               placeholder="Enter full name" 
               value="{{ old('name', $student->user->name ?? '') }}" 
               required>
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Email Address (User Table se) --}}
    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Email Address</label>
        <input type="email" name="email" 
               class="form-control morphism-input @error('email') is-invalid @enderror" 
               placeholder="Enter email" 
               value="{{ old('email', $student->user->email ?? '') }}" 
               required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Fathers Name</label>
        <input type="text" name="father_name" 
               class="form-control morphism-input @error('father_name') is-invalid @enderror" 
               placeholder="Fathers Name" 
               value="{{ old('father_name', $student->father_name ?? '') }}" 
               required>
        @error('father_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    
   <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Roll Number</label>
        <input type="text" name="roll_no" 
               class="form-control morphism-input @error('roll_no') is-invalid @enderror" 
               placeholder="Assign Roll No." 
               value="{{ old('roll_no', $student->roll_no ?? '') }}">
        @error('roll_no') <div class="invalid-feedback d-block small ms-1">{{ $message }}</div> @enderror
    </div>

    {{-- Date of Birth (Student Table se) --}}
    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Date of Birth</label>
        <input type="date" name="dob" 
               class="form-control morphism-input @error('dob') is-invalid @enderror" 
               value="{{ old('dob', isset($student->dob) ? substr($student->dob,0,10): '') }}" 
               required>
        @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Class (Student Table se) --}}
    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Class</label>
        <select name="class" class="form-control morphism-input @error('class') is-invalid @enderror" required>
            <option value="" disabled {{ !isset($student->class) ? 'selected' : '' }}>Select Class</option>
            @foreach(['9', '10', '11', '12'] as $cls)
                <option value="{{ $cls }}" {{ old('class', $student->class ?? '') == $cls ? 'selected' : '' }}>{{ $cls }}th</option>
            @endforeach
        </select>
        @error('class') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    

    {{-- Phone Number (Student Table se) --}}
    <div class="col-md-12">
        <label class="form-label fw-bold text-muted ml-1">Phone Number</label>
        <input type="tel" name="phone" 
               class="form-control morphism-input @error('phone') is-invalid @enderror" 
               placeholder="10 digit number" 
               value="{{ old('phone', $student->phone ?? '') }}" 
               required 
               pattern="[0-9]{10}">
        @error('phone') <div class="invalid-feedback d-block small ms-1">{{ $message }}</div> @enderror
    </div>
</div>
{{-- Button Logic & Design --}}
<div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-3 gap-sm-3 mt-4 mt-sm-5">
    <button type="submit" class="btn btn-success btn-3d-success px-5 py-2 fw-bold">
        {{-- Button text dynamically change hoga --}}
        @if(isset($student->id))
            <i class="fas fa-check-circle me-1"></i> Update Data
        @else
            <i class="fas fa-save me-1"></i> Save Student
        @endif
    </button>
    <a href="{{ route('admin.students.index') }}" class="btn btn-3d-secondary px-5 py-2 fw-bold">
        <i class="fas fa-arrow-left me-1"></i> Go Back
    </a>
</div>