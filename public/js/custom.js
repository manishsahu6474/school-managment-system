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

// Success Message ke liye Shiny 3D Function
function showSuccessAlert(message) {
    Swal.fire({
        title: 'Success!',
        text: message,
        icon: 'success',
        background: 'rgba(255, 255, 255, 0.9)', // Glassy Background
        backdrop: `rgba(0, 122, 255, 0.1) blur(4px)`, // Apple Style Blur
        showConfirmButton: false,
        timer: 2000,
        customClass: {
            popup: 'card-morphism border-0 shadow-lg', // 3D Card Look
            title: 'fw-bold text-success',
        },
        // Shiny Icon Animation
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    });
}
// delete ki functionalty
// public/js/custom.js
function deleteStudent(id) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#00d2ff', // Shiny Blue
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        background: 'rgba(255, 255, 255, 0.95)',
        backdrop: `rgba(0,0,0,0.4) blur(5px)` // Glass effect
    }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-form-' + id).submit();
        }
    });
}
//logout ki functionalty
function logoutConfirm() {
    Swal.fire({
        title: 'Logout Karein?',
        text: "Kya aap sach mein session band karna chahte hain?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Haan, Logout!',
        cancelButtonText: 'Nahi, ruko',
        // Aapka purana 3D look yahan bhi kaam karega
        customClass: {
            popup: 'card-morphism'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}
