<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-morphism text-center p-4 h-100">
                    <div class="position-relative d-inline-block mx-auto mb-3">
                        <img id="profile-preview"
                            src="{{ auth()->user()->profile_image
                                ? asset('storage/' . auth()->user()->profile_image)
                                : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=224abe&color=fff&bold=true' }}"
                            class="rounded-circle shadow-lg border border-4 border-white" width="150" height="150"
                            style="object-fit: cover; aspect-ratio: 1/1;"
                            alt="{{ auth()->user()->name }}'s profile photo">
                        <label for="profile_image"
                            class="btn btn-sm btn-primary position-absolute bottom-0 end-0 rounded-circle action-btn">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">{{ auth()->user()->name }}</h4>
                    <p class="text-primary mb-3">System Administrator</p>
                    <div class="title-underline mx-auto"></div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card card-morphism p-4 border-0 shadow-lg">
                    <h6 class="fw-bold text-dark mb-4"><i class="fas fa-user-edit me-2 text-primary"></i> Edit Profile
                        Details</h6>

                    <form id="profileUpdateForm" action="{{ route('admin.update-profile') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="profile_image" id="profile_image" class="d-none">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Full Name</label>
                                <input type="text" name="name" class="form-control morphism-input"
                                    value="{{ auth()->user()->name }}" placeholder="Enter Full Name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Email Address</label>
                                <input type="email" name="email" class="form-control morphism-input"
                                    value="{{ auth()->user()->email }}" placeholder="Enter email" required>
                            </div>

                            <hr class="my-4 opacity-0">
                            <h6 class="fw-bold text-danger mb-2"><i class="fas fa-lock me-2"></i> Security (Leave blank
                                to keep current)</h6>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">New Password</label>
                                <input type="password" name="password" class="form-control morphism-input"
                                    placeholder="Enter new password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control morphism-input"
                                    placeholder="Repeat new password">
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-3d-primary px-5 fw-bold">
                                <i class="fas fa-save me-1"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('profile_image').onchange = evt => {
            const [file] = document.getElementById('profile_image').files
            if (file) {
                document.getElementById('profile-preview').src = URL.createObjectURL(file)
            }
        }
    </script>
</x-app-layout>
