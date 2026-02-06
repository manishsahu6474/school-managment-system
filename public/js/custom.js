document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('menu-toggle');

    if (toggleBtn && sidebar) {
        // 1. Sirf click karne par sidebar dikhega
        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation(); // Click event ko document tak jane se rokein
            sidebar.classList.toggle('active');
        });

        // 2. Bahar click karne par smooth hide logic
        document.addEventListener('click', function (event) {
            // Agar click sidebar ke andar nahi hai aur toggle button par bhi nahi hai
            if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                if (sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            }
        });
    }
});
document.addEventListener('DOMContentLoaded', function () {
    // Aapka purana sidebar toggle code yahan rahega

    // --- Naya Delete Logic ---
    const deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            const form = this.closest('form'); // Button ke parent form ko pakdega

            Swal.fire({
                title: 'Are you sure?',
                text: "Student ka data delete ho jayega!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    popup: 'swal2-3d-popup' // Aapki 3D styling
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Agar user 'Yes' kahe tabhi delete hoga
                }
            });
        });
    });
});