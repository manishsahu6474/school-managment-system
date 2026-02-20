/* ==========================================
   1. SIDEBAR & UI LOGIC
   ========================================== */
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('menu-toggle');

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            sidebar.classList.toggle('active');
        });

        document.addEventListener('click', function (event) {
            if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                if (sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            }
        });
    }
});

/* ==========================================
   2. SHINY 3D ALERTS 
   ========================================== */
function showSuccessAlert(message) {
    Swal.fire({
        title: 'Success!',
        text: message,
        icon: 'success',
        background: 'rgba(255, 255, 255, 0.9)',
        backdrop: `rgba(0, 122, 255, 0.1) blur(4px)`,
        showConfirmButton: true,
        customClass: {
            popup: 'card-morphism border-0 shadow-lg',
            title: 'fw-bold text-success',
        },
        showClass: { popup: 'animate__animated animate__fadeInDown' },
        hideClass: { popup: 'animate__animated animate__fadeOutUp' },
    });
}

function showinfoAlert(message) {
    Swal.fire({
        title: 'Info!',
        text: message,
        icon: 'info',
        background: 'rgba(255, 255, 255, 0.9)',
        backdrop: `rgba(0, 122, 255, 0.1) blur(4px)`,
        showConfirmButton: true,
        customClass: {
            popup: 'card-morphism border-0 shadow-lg',
            title: 'fw-bold text-info',
        },
        showClass: { popup: 'animate__animated animate__fadeInDown' },
        hideClass: { popup: 'animate__animated animate__fadeOutUp' }
    });
}

/* ==========================================
   3. DELETE / INACTIVE FUNCTIONALITY
   ========================================== */
function deleteStudent(id) {
    // jQuery use kar rahe hain taaki elements asani se mil jayein
    const $form = $('#delete-form-' + id);
    const url = $form.attr('action');

    Swal.fire({
        title: 'Are you sure?',
        text: "Student ko Inactive kar diya jayega!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#142aed',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Inactive it!',
        background: 'rgba(255, 255, 255, 0.95)',
        backdrop: `rgba(0,0,0,0.4) blur(5px)`,
        customClass: { popup: 'card-morphism border-0 shadow-lg' }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function (response) {
                    if (response.status === 'success') {
                        showSuccessAlert(response.message);
                        let $row = $form.closest('tr');
                        let $statusBtn = $row.find('button[onclick="confirmStatusChange(this)"]');

                        $statusBtn.text('Inactive');
                        $statusBtn.removeClass('btn-success').addClass('btn-secondary');

                    }
                    else if (response.status === 'info') {
                        showinfoAlert(response.message);
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error!', 'Action perform nahi ho paya.', 'error');
                }
            });
        }
    });
}
function deleteTeacher(id) {
    // jQuery use kar rahe hain taaki elements asani se mil jayein
    const $form = $('#delete-form-' + id);
    const url = $form.attr('action');

    Swal.fire({
        title: 'Are you sure?',
        text: "Teacher delete ho jyega!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#142aed',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete it!',
        background: 'rgba(255, 255, 255, 0.95)',
        backdrop: `rgba(0,0,0,0.4) blur(5px)`,
        customClass: { popup: 'card-morphism border-0 shadow-lg' }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function (response) {
                    showSuccessAlert(response.message);
                },
                error: function (xhr) {
                    Swal.fire('Error!', 'Action perform nahi ho paya.', 'error');
                }
            });
        }
    });
}

/* ==========================================
   4. STATUS TOGGLE FUNCTIONALITY
   ========================================== */
function confirmStatusChange(button) {
    const $btn = $(button); // Button ko jQuery object mein convert kiya
    const $form = $btn.closest('form');
    const url = $form.attr('action');

    Swal.fire({
        title: 'Status badlein?',
        text: "Kya aap student ka status change karna chahte hain?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#256de1',
        confirmButtonText: 'Haan, badal do!',
        background: 'rgba(255, 255, 255, 0.95)',
        backdrop: `rgba(0,0,0,0.4) blur(5px)`,
        customClass: { popup: 'card-morphism border-0 shadow-lg' }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.status === 'success') {
                        // UI Update: Active <-> Inactive toggle
                        if ($btn.text().trim() === 'Active') {
                            $btn.text('Inactive');
                            $btn.removeClass('btn-success').addClass('btn-secondary');
                        } else {
                            $btn.text('Active');
                            $btn.removeClass('btn-secondary').addClass('btn-success');
                        }
                        showSuccessAlert(response.message);
                    }
                },
                error: function (xhr) {
                    Swal.fire('Error!', 'Status update nahi hua.', 'error');
                }
            });
        }
    });
}

/* ==========================================
   5. LOGOUT FUNCTIONALITY
   ========================================== */
function logoutConfirm() {
    Swal.fire({
        title: 'Logout Karein?',
        text: "Kya aap sach mein session band karna chahte hain?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Haan, Logout!',
        customClass: { popup: 'card-morphism border-0 shadow-lg' }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logout-form').submit();
        }
    });
}
/* ==========================================
   6. Select All Logic and Bulk Promte
   ========================================== */
$(document).ready(function () {

    $('#master-checkbox').on('click', function () {
        $('.student-checkbox').prop('checked', this.checked);
        togglePromoteBtn();
    });

    $('.student-checkbox').on('change', function () {
        togglePromoteBtn();
    });

    function togglePromoteBtn() {
        if ($('.student-checkbox:checked').length > 0) {
            $('#bulk-promote-btn').fadeIn();
        } else {
            $('#bulk-promote-btn').fadeOut();
        }
    }
});
function bulkPromote() {
    let selectedIds = [];
    $('.student-checkbox:checked').each(function () {
        selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) {
        Swal.fire('Opps!', 'First select students.', 'info');
        return;
    }

    Swal.fire({
        title: 'Promote Students?',
        text: selectedIds.length + " students to be promote next class.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Promote Now',
        confirmButtonColor: '#256de1',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/admin/students/bulk-promote",
                type: "POST",
                data: {
                    ids: selectedIds,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    Swal.fire('Success!', response.message, 'success');
                    location.reload();
                }
            });
        }
    });
}