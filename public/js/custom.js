/* ==========================================
   1. SIDEBAR & UI LOGIC
   ========================================== */
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('menu-toggle');
    let hideTimer;

    if (toggleBtn && sidebar) {

        // Function: Sidebar ko band karne ke liye
        function hideSidebar() {
            if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        }

        // Function: Timer ko reset karne ke liye
        function resetTimer() {
            clearTimeout(hideTimer);
            // Agar mobile hai aur sidebar active hai, toh naya timer shuru karo
            if (window.innerWidth <= 768 && sidebar.classList.contains('active')) {
                hideTimer = setTimeout(hideSidebar, 7000); // 7 Seconds ka wait
            }
        }

        // 1. Toggle Button Click
        toggleBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            sidebar.classList.toggle('active');
            resetTimer(); // Timer shuru 
        });

        // 2. Bahar Click karne par turant hide
        document.addEventListener('click', function (event) {
            if (window.innerWidth <= 768 && !sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                hideSidebar();
            }
        });

        // 3. Scroll par turant hide 
        window.addEventListener('scroll', function () {
            hideSidebar();
        }, { passive: true });

        // 4. Activity check
        sidebar.addEventListener('mousemove', resetTimer);
        sidebar.addEventListener('touchstart', resetTimer);
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
        focusConfirm: false,
        buttonsStyling: false,
        showClass: { popup: 'animate__animated animate__fadeInDown' },
        hideClass: { popup: 'animate__animated animate__fadeOutUp' },
        customClass: {
            popup: 'card-morphism border-0 shadow-lg',
            title: 'fw-bold text-success',
            confirmButton: 'btn-3d-success px-4 py-2'
        },
    });

}

function showinfoAlert(message) {
    Swal.fire({
        title: 'Info!',
        text: message,
        icon: 'info',
        background: 'rgba(255, 255, 255, 0.9)',
        backdrop: `rgba(0, 122, 255, 0.1) blur(4px)`,
        //showConfirmButton: true,
        focusConfirm: false,
        buttonsStyling: false,
        showClass: { popup: 'animate__animated animate__fadeInDown' },
        hideClass: { popup: 'animate__animated animate__fadeOutUp' },
        customClass: {
            popup: 'card-morphism border-0 shadow-lg',
            title: 'fw-bold text-info',
            confirmButton: 'btn-3d-primary px-4 py-2'
        },
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
        confirmButtonText: 'Yes, Inactive it!',
        background: 'rgba(255, 255, 255, 0.95)',
        backdrop: `rgba(0,0,0,0.4) blur(5px)`,
        customClass: {
            popup: 'card-morphism border-0 shadow-lg',
            confirmButton: 'btn-3d-danger  px-4 py-2',
            cancelButton: 'btn-3d-secondary px-4 py-2'
        }
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
        confirmButtonText: 'Yes, Delete it!',
        background: 'rgba(255, 255, 255, 0.95)',
        backdrop: `rgba(0,0,0,0.4) blur(5px)`,
        customClass: {
            popup: 'card-morphism border-0 shadow-lg',
            confirmButton: 'btn-3d-danger  px-4 py-2',
            cancelButton: 'btn-3d-secondary px-4 py-2'
        }
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

/* ==========================================
   4. STATUS TOGGLE FUNCTIONALITY
   ========================================== */
function confirmStatusChange(button) {
    const $btn = $(button); // Button ko jQuery object mein convert kiya
    const $form = $btn.closest('form');
    const url = $form.attr('action');

    Swal.fire({
        title: 'Status badlein?',
        text: "Kya aap Inki  status change karna chahte hain?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Haan, badal do!',
        background: 'rgba(255, 255, 255, 0.95)',
        backdrop: `rgba(0,0,0,0.4) blur(5px)`,
        customClass: {
            popup: 'card-morphism border-0 shadow-lg',
            confirmButton: 'btn-3d-success px-4 py-2',
            cancelButton: 'btn-3d-secondary px-4 py-2'
        }
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
/* ==========================================
   7. 3d stylish Bar & Pie Chart 
   ========================================== */

document.addEventListener('DOMContentLoaded', function () {
    const statsElement = document.getElementById('stats-data');

    // Agar element nahi mila, toh yahin se wapas ho jao (Stop execution)
    if (!statsElement) {
        return;
    }
    const data = JSON.parse(statsElement.value);

    const getGradient = (ctx, color1, color2) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, color1);
        gradient.addColorStop(1, color2);
        return gradient;
    };

    const renderFullChart = (ctxId, counts, isPie = false) => {
        const canvas = document.getElementById(ctxId);
        let chartStatus = Chart.getChart(ctxId);
        if (chartStatus !== undefined) {
            chartStatus.destroy();
        }
        const ctx = canvas.getContext('2d');

        const gradients = [
            getGradient(ctx, '#007AFF', '#00C6FF'), // Total - Blue
            getGradient(ctx, '#34C759', '#32E0C4'), // Active - Green
            getGradient(ctx, '#FF3B30', '#FF9500')  // Inactive - Orange
        ];

        new Chart(ctx, {
            type: isPie ? 'doughnut' : 'bar',
            data: {
                labels: isPie ? ['Active', 'Inactive'] : ['Total', 'Active', 'Inactive'],
                datasets: [{
                    data: isPie ? [counts.active, counts.inactive] : [counts.total, counts.active, counts.inactive],
                    backgroundColor: isPie ? [gradients[1], gradients[2]] : gradients,
                    borderRadius: isPie ? 0 : 10,
                    borderWidth: 0,
                    cutout: '70%',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                resizeDelay: 200,
                layout: {
                    padding: 0
                }, // Phone responsive ke liye zaroori hai
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart',
                    delay: (context) => context.dataIndex * 150 // Ek-ek karke bars aayengi
                },
                plugins: {
                    legend: {
                        display: isPie, position: 'bottom',
                        labels: { boxWidth: 8, font: { size: 10 }, padding: 10 }
                    },
                    tooltip: { enabled: true }
                },
                scales: isPie ? {} : {
                    y: { beginAtZero: true, grid: { display: false }, ticks: { display: false }, border: { display: false } },
                    x: { grid: { display: false }, border: { display: false } }
                }
            }
        });
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id;

                // Jo chart screen par aaya, sirf use render karo
                if (id === 'studentBar') renderFullChart('studentBar', data.student);
                if (id === 'studentPie') renderFullChart('studentPie', data.student, true);
                if (id === 'teacherBar') renderFullChart('teacherBar', data.teacher);
                if (id === 'teacherPie') renderFullChart('teacherPie', data.teacher, true);

                // Ek baar render hone ke baad observer hata dein (sirf ek baar animate hoga)
                // observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 }); // Jab 10% chart dikhne lage tab trigger hoga

    // Saare canvas elements ko observe karna shuru karein
    document.querySelectorAll('canvas').forEach(canvas => {
        observer.observe(canvas);
    });
});

/* ==========================================
   8. Back Button Proble Solution 
   ========================================== */


window.addEventListener('pageshow', function (event) {
    // Agar user back button se aaya hai (persisted) ya history navigation use ki hai
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
        window.location.reload();
    }
});