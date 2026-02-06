document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('menu-toggle');

    if (toggleBtn && sidebar) {
        // Single Toggle Event
        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            sidebar.classList.toggle('active');
            console.log("Sidebar Status: ", sidebar.classList.contains('active'));
        });

        // Mobile par bahar click karne par hide hona
        document.addEventListener('click', function (event) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        // Mouse leave hone par auto-hide (Mobile Only)
        sidebar.addEventListener('mouseleave', function() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
            }
        });
    }
});

document.addEventListener('DOMContentLoaded', function() { 
    // Check if success message exists
    const successElement = document.getElementById('session-success');
    
    if (successElement) {
        const message = successElement.getAttribute('data-message');
        
        Swal.fire({
            title: 'Hooray!',
            text: message,
            icon: 'success',
            background: '#ffffff',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            // 3D Shadow and Animation
            customClass: {
                popup: 'swal2-3d-popup',
                title: 'swal2-3d-title'
            },
            showClass: {
                popup: 'animate__animated animate__backInDown' // Optional: subtle animation
            }
        });
    }
});
// Example for 3D Delete Confirmation
$(document).on('click', '.delete-confirm', function(e) {
    e.preventDefault();
    let form = $(this).closest('form');
    
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        customClass: {
            popup: 'swal2-3d-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
