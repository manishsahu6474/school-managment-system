<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Teacher Name</label>
        <input type="text" name="name" class="form-control morphism-input @error('name') is-invalid @enderror"
            placeholder="Enter full name" value="{{ old('name', $teacher->user->name ?? '') }}" required>
        @error('name')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-bold text-muted ml-1">Email Address</label>
        <input type="email" name="email" class="form-control morphism-input @error('email') is-invalid @enderror"
            placeholder="Enter email" value="{{ old('email', $teacher->user->email ?? '') }}" required>
        @error('email')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-bold text-muted ml-1">Joining Date</label>
        <input type="date" name="joining_date"
            class="form-control morphism-input @error('joining_date') is-invalid @enderror"
            value="{{ old('joining_date', isset($teacher) ? substr($teacher->joining_date, 0, 10) : '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-bold text-muted ml-1">Subject</label>
        <select name="subject" class="form-select morphism-input @error('subject') is-invalid @enderror">
            <option value="">Select Subject</option>
            @foreach (['Maths', 'Science', 'English', 'Hindi', 'Physics', 'Chemistry'] as $sub)
                <option value="{{ $sub }}"
                    {{ old('subject', $teacher->subject ?? '') == $sub ? 'selected' : '' }}>{{ $sub }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-bold text-muted ml-1">Qualification</label>
        <select name="qualification" class="form-select morphism-input @error('qualification') is-invalid @enderror">
            <option value="">Select Qualification</option>
            @foreach (['B.Ed', 'M.Sc', 'Ph.D', 'MA'] as $qual)
                <option value="{{ $qual }}"
                    {{ old('qualification', $teacher->qualification ?? '') == $qual ? 'selected' : '' }}>
                    {{ $qual }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-bold text-muted ml-1">Experience (Years)</label>
        <input type="number" name="experience"
            class="form-control morphism-input @error('experience') is-invalid @enderror" placeholder="e.g. 5"
            value="{{ old('experience', $teacher->experience ?? '') }}" min="0">
    </div>

    <div class="col-md-4">
        <label class="form-label fw-bold text-muted ml-1">Monthly Salary (₹)</label>
        <input type="number" name="salary" class="form-control morphism-input @error('salary') is-invalid @enderror"
            placeholder="Enter salary" value="{{ old('salary', $teacher->salary ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label fw-bold text-muted ml-1">Gender</label>
        <select name="gender" class="form-select morphism-input">
            <option value="" disabled {{ old('gender', $teacher->gender ?? '') == '' ? 'selected' : '' }}>Select
                Gender</option>
            <option value="male" {{ old('gender', $teacher->gender ?? '') == 'male' ? 'selected' : '' }}>Male
            </option>
            <option value="female" {{ old('gender', $teacher->gender ?? '') == 'female' ? 'selected' : '' }}>Female
            </option>
        </select>
    </div>
    <div class="row g-4">
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
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold text-muted ml-1">Monthly Salary (₹)</label>
            <input type="number" name="salary"
                class="form-control morphism-input @error('salary') is-invalid @enderror" placeholder="Enter salary"
                value="{{ old('salary', $teacher->salary ?? '') }}">
        </div>

        <div class="col-md-12">
            <label class="form-label fw-bold text-muted ml-1">Residential Address</label>
            <textarea name="address" rows="3" class="form-control morphism-input @error('address') is-invalid @enderror"
                placeholder="Enter full residential address">{{ old('address', $teacher->address ?? '') }}</textarea>
            @error('address')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

    </div>
    <div class="col-md-12">
        <label class="form-label fw-bold text-muted ml-1">Password
            {{ isset($teacher) ? '(Leave blank to keep current)' : '' }}</label>
        <input type="password" name="password"
            class="form-control morphism-input @error('password') is-invalid @enderror"
            placeholder="Create a strong password">
        @error('password')
            <span class="text-danger small">{{ $message }}</span>
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
