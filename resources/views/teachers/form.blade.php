<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Teacher Name</label>
        <input type="text" name="name" class="form-control morphism-input @error('name') is-invalid @enderror"
            placeholder="Enter full name" value="{{ old('name', $teacher->user->name ?? '') }}" required>
        @error('name')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Email Address</label>
        <input type="email" name="email" class="form-control morphism-input @error('email') is-invalid @enderror"
            placeholder="Enter email" value="{{ old('email', $teacher->user->email ?? '') }}" required>
        @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-bold text-muted ml-1">Joining Date</label>
        <input type="date" name="joining_date"
            class="form-control morphism-input @error('joining_date') is-invalid @enderror"
             max="{{ date('Y-m-d') }}" value="{{ old('joining_date', isset($teacher) ? substr($teacher->joining_date, 0, 10) : '') }}" required>
        @error('joining_date')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold text-muted ml-1">Class</label>
        <select name="class_id" class="form-select morphism-input @error('class_id') is-invalid @enderror" required>
            <option value="">Select class</option>
            @foreach ($classes as $class)
                <option value="{{ $class->id }}"
                    {{ old('class_id') == $class->id || (isset($teacher) && $teacher->subjects()->wherePivot('class_id', $class->id)->exists()) ? 'selected' : '' }}>
                    {{ $class->class_name }}th
                </option>
            @endforeach
        </select>
        @error('class_id')
            <span class="invalid-feedback">Please Select a Class</span>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold text-muted ml-1">Subject</label>
        <select name="subject_id" class="form-select morphism-input @error('subject_id') is-invalid @enderror" required>
            <option value="">Select Subject</option>
            @foreach ($subjects as $subject)
                <option value="{{ $subject->id }}"
                    {{ old('subject_id') == $subject->id || (isset($teacher) && $teacher->subjects->contains($subject->id)) ? 'selected' : '' }}>
                    {{ $subject->subject_name }}
                </option>
            @endforeach
        </select>
        @error('subject_id')
            <span class="invalid-feedback">Please Select a Subject</span>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold text-muted ml-1">Qualification</label>
        <select name="qualification" class="form-select morphism-input @error('qualification') is-invalid @enderror"
            required>
            <option value="">Select Qualification</option>
            @foreach (['B.Ed', 'M.Sc', 'Ph.D', 'MA'] as $qual)
                <option value="{{ $qual }}"
                    {{ old('qualification', $teacher->qualification ?? '') == $qual ? 'selected' : '' }}>
                    {{ $qual }}</option>
            @endforeach
        </select>
        @error('qualification')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-bold text-muted ml-1">Experience (Years)</label>
        <input type="number" name="experience"
            class="form-control morphism-input @error('experience') is-invalid @enderror" placeholder="e.g. 5"
         maxlength="30"   value="{{ old('experience', $teacher->experience ?? '') }}" min="0" required>
        @error('experience')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold text-muted ml-1">Gender</label>
        <select name="gender" class="form-select morphism-input @error('gender') is-invalid @enderror" required>
            <option value="">Select Gender</option>
            <option value="male" {{ old('gender', $teacher->gender ?? '') == 'male' ? 'selected' : '' }}>Male
            </option>
            <option value="female" {{ old('gender', $teacher->gender ?? '') == 'female' ? 'selected' : '' }}>Female
            </option>
        </select>
        @error('gender')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Phone Number</label>
        <div class="input-group">
            <span class="input-group-text morphism-input border-end-0 text-muted">+91</span>
            <input type="text" name="phone"
                class="form-control morphism-input @error('phone') is-invalid @enderror"
                placeholder="10-digit mobile number" value="{{ old('phone', $teacher->phone ?? '') }}" required
                pattern="[0-9]{10}">
        </div>
        @error('phone')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Monthly Salary (₹)</label>
        <input type="number" name="salary" class="form-control morphism-input @error('salary') is-invalid @enderror"
          min="1000"  placeholder="Enter salary" value="{{ old('salary', $teacher->salary ?? '') }}" required>
        @error('salary')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-12">
        <label class="form-label fw-bold text-muted ml-1">Residential Address</label>
        <textarea name="address" rows="3" class="form-control morphism-input @error('address') is-invalid @enderror"
          minlength="10"  placeholder="Enter full residential address">{{ old('address', $teacher->address ?? '') }}</textarea>
        @error('address')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-12">
        <label class="form-label fw-bold text-muted ml-1">Password
            @if (isset($teacher) && $teacher->exists)
                <div class="text-info small">(Leave blank to keep current password)</div>
            @endif
        </label>
        <input type="password" name="password"
            class="form-control morphism-input @error('password') is-invalid @enderror"
          minlength="8"  placeholder="Create a strong password" {{ isset($teacher) && $teacher->exists ? '' : 'required' }}>
        @error('password')
            <span class="invalid-feedback">{{ $message }}</span>
        @enderror
    </div>

    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-3 gap-sm-3 mt-4 mt-sm-5">
        <button type="submit" class="btn btn-success btn-3d-success px-5 py-2 fw-bold">
            <i class="fas fa-check-circle me-1"></i> {{ isset($teacher) ? 'Update Data' : 'Save Teacher' }}
        </button>
        <a href="{{ route('admin.teachers.index') }}" class="btn btn-3d-secondary px-5 py-2 fw-bold">
            <i class="fas fa-arrow-left me-1"></i> Go Back
        </a>
    </div>
</div>
