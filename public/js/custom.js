/**
 * Global Alert Handler 
 */
const showAlert = (title, text, icon, colorClass = 'text-primary') => {
    Swal.fire({
        title: title,
        text: text,
        icon: icon,
        background: 'rgba(255, 255, 255, 0.9)',
        backdrop: `rgba(0, 122, 255, 0.1) blur(4px)`,
        timer: 3000,
        timerProgressBar: true,
        showClass: { popup: 'animate__animated animate__fadeInDown' },
        hideClass: { popup: 'animate__animated animate__fadeOutUp' },
        customClass: {
            popup: 'card-morphism border-0 shadow-lg',
            title: `fw-bold ${colorClass}`,
            cancelButton: 'btn-3d-secondary'
        },
    });
};
/**
 * GLOBAL CONSTANTS & HELPERS
 */
const csrfToken = $('meta[name="csrf-token"]').attr('content');

// Common Ajax Error Handler
const handleAjaxError = (xhr, status, $btn, originalHtml) => {
    if ($btn) $btn.prop('disabled', false).html(originalHtml);
    if (xhr.status === 403) {
        const res = xhr.responseJSON;
        return showAlert('Access Denied!', res.message, 'error', 'text-danger');
    }
    let msg = 'Connection lost. Please try again.';
    if (status === 'timeout') msg = 'Server slow! Request timed out.';
    else if (xhr.status === 419) msg = 'Session expired. Please refresh.';
    else if (xhr.responseJSON?.message) msg = xhr.responseJSON.message;
    Swal.fire('Error!', msg, 'error');
};

/**
 * CORE ENGINE
 */
function executeAjaxAction(url, data, config, $btn = null, originalHtml = '', $row = null, method = 'POST') {
    $.ajax({
        url: url,
        type: "POST",
        timeout: 10000,
        data: { ...data, _token: csrfToken, _method: method },
        beforeSend: () => { if ($btn) $btn.prop('disabled', true).html('<span class="btn-spinner"></span>'); },
        success: (res) => {
            if (res.status === 'success') {
                if (config.msgKey) localStorage.setItem(config.msgKey, res.message);
                if ($row) {
                    $row.fadeOut(400, () => location.reload());
                } else {
                    location.reload();
                }
            } else {
                if ($btn) $btn.prop('disabled', false).html(originalHtml);
                Swal.fire('Info', res.message, 'info');
            }
        },
        error: (xhr, status) => handleAjaxError(xhr, status, $btn, originalHtml)
    });
}

/**
 * 1. SIDEBAR LOGIC
 */
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('menu-toggle');
    let hideTimer;

    if (!sidebar || !toggleBtn) return;

    const resetTimer = () => {
        clearTimeout(hideTimer);
        if (window.innerWidth <= 1199 && sidebar.classList.contains('active')) {
            hideTimer = setTimeout(() => sidebar.classList.remove('active'), 7000);
        }
    };

    toggleBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('active');
        resetTimer();
    });

    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    });

    window.addEventListener('scroll', () => sidebar.classList.remove('active'), { passive: true });
    sidebar.addEventListener('touchstart', resetTimer, { passive: true });
});

/**
 * 2. AUTO-ALERTS
 */
$(document).ready(() => {
    const alerts = {
        delete_success_msg: 'text-danger',
        activated_success_msg: 'text-success',
        update_success_msg: 'text-primary',
        error_msg: 'text-danger'
    };

    Object.keys(alerts).forEach(key => {
        const msg = localStorage.getItem(key);
        if (msg) {
            Swal.fire({
                text: msg, icon: key.includes('error') ? 'error' : 'success',
                timer: 2000, timerProgressBar: true,
                customClass: { popup: 'card-morphism', title: `fw-bold ${alerts[key]}` }
            });
            localStorage.removeItem(key);
        }
    });
});

/**
 * 3. WRAPPER FUNCTIONS 
 */

function approveStudent(button) {
    const $btn = $(button);
    const $form = $btn.closest('form');
    const isApprove = $btn.hasClass('btn-approve');

    Swal.fire({
        title: isApprove ? 'Approve Admission?' : 'Re-Activate Student?',
        text: "Kya aap is student ko Active list mein move karna chahte hain?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Activate!',
        customClass: { popup: 'card-morphism', confirmButton: 'btn-3d-success', cancelButton: 'btn-3d-secondary' }
    }).then((result) => {
        if (result.isConfirmed) {
            executeAjaxAction($form.attr('action'), { status: 1 }, { msgKey: 'activated_success_msg' }, $btn, $btn.html(), $form.closest('tr'));
        }
    });
}
const activateStudent = approveStudent;

function deleteStudent(id, status, btn = null) {
    const $form = $('#delete-form-' + id);
    const isInactiveAction = (status == 1);

    Swal.fire({
        title: isInactiveAction ? 'Inactive karein?' : 'Delete karein?',
        text: isInactiveAction ? 'Student Inactive list mein chala jayega.' : 'Permanently delete ho jayega!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Proceed',
        customClass: { popup: 'card-morphism', confirmButton: 'btn-3d-danger', cancelButton: 'btn-3d-secondary' }
    }).then((result) => {
        if (result.isConfirmed) {
            executeAjaxAction($form.attr('action'), {}, { msgKey: 'delete_success_msg' }, $(btn), $(btn).html(), $form.closest('tr'), 'DELETE');
        }
    });
}

function deletesubject(id, btn = null) {
    const $form = $('#delete-form-' + id);
    Swal.fire({
        title: 'Delete karein?',
        text: 'Subject Permanently delete ho jayega!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Proceed',
        customClass: { popup: 'card-morphism', confirmButton: 'btn-3d-danger', cancelButton: 'btn-3d-secondary' }
    }).then((result) => {
        if (result.isConfirmed) {
            executeAjaxAction($form.attr('action'), {}, { msgKey: 'delete_success_msg' }, $(btn), $(btn).html(), $form.closest('tr'), 'DELETE');
        }
    });
}

/**
 * 4. BULK ACTIONS ENGINE 
 */
const GLOBAL_CHECKBOX = '.record-checkbox';

$(document).ready(() => {
    $('#master-checkbox').on('click', function () {
        $(GLOBAL_CHECKBOX).prop('checked', this.checked);
        toggleBulkWrapper();
    });

    $(document).on('change', GLOBAL_CHECKBOX, function () {
        const total = $(GLOBAL_CHECKBOX).length;
        const checked = $(GLOBAL_CHECKBOX + ':checked').length;
        $('#master-checkbox').prop('checked', (checked === total && total > 0));
        toggleBulkWrapper();
    });

    function toggleBulkWrapper() {
        const checkedCount = $(GLOBAL_CHECKBOX + ':checked').length;
        const $wrapper = $('#bulk-actions-wrapper');
        if (checkedCount > 0) {
            if ($wrapper.is(':hidden')) {
                $wrapper.stop(true, true).slideDown(400).fadeIn(400).addClass('show-active');
            }
        } else {
            $wrapper.stop(true, true).slideUp(300).fadeOut(200).removeClass('show-active');
            $('#master-checkbox').prop('checked', false);
        }
    }
});

function executeBulkAction(config) {
    let ids = $(GLOBAL_CHECKBOX + ':checked').map(function () { return $(this).val(); }).get();

    if (!ids.length) {
        return Swal.fire('Oops!', `Pehle ${config.entity || 'records'} select karein.`, 'info');
    }

    Swal.fire({
        title: config.title,
        text: `${ids.length} ${config.entity} selected. ${config.text}`,
        icon: config.icon || 'question',
        showCancelButton: true,
        confirmButtonText: config.confirmText,
        customClass: { popup: 'card-morphism', confirmButton: config.btnClass, cancelButton: 'btn-3d-secondary' }
    }).then((res) => {
        if (res.isConfirmed) {
            executeAjaxAction(config.url, { ids: ids, _method: 'POST' }, { msgKey: config.msgKey });
        }
    });
}

const bulkPromote = () => executeBulkAction({
    entity: 'students',
    title: 'Promote Students?',
    text: 'Selected students next class mein promote ho jayenge.',
    confirmText: 'Promote Now',
    btnClass: 'btn-3d-primary',
    url: "/admin/students/bulk-promote",
    msgKey: 'update_success_msg'
});

const bulkApprove = () => executeBulkAction({
    entity: 'students',
    title: 'Approve Selected?',
    text: 'In sabhi students ka admission approve ho jayega.',
    confirmText: 'Yes, Approve All',
    btnClass: 'btn-3d-success',
    url: "/admin/students/bulk-approve",
    msgKey: 'activated_success_msg'
});

function bulkStudentDelete(isPermanent = false) {
    executeBulkAction({
        entity: 'students',
        title: isPermanent ? 'Delete Selected?' : 'Make Inactive?',
        text: isPermanent ? 'Ye records hamesha ke liye delete ho jayenge!' : 'Ye students inactive list mein chale jayenge.',
        icon: 'warning',
        confirmText: isPermanent ? 'Yes, Delete' : 'Yes, Inactivate',
        btnClass: 'btn-3d-danger',
        method: isPermanent ? 'DELETE' : 'POST',
        url: isPermanent ? "/admin/students/bulk-delete" : "/admin/students/bulk-inactivate",
        msgKey: 'delete_success_msg'
    });
}

const bulkActivate = () => executeBulkAction({
    entity: 'students',
    title: 'Re-Activate Selected?',
    text: 'Selected students ko dobara Active list mein move kiya jayega.',
    icon: 'info',
    confirmText: 'Yes, Activate All',
    btnClass: 'btn-3d-primary',
    url: "/admin/students/bulk-activate",
    msgKey: 'activated_success_msg'
});

const bulkTeacherApprove = () => executeBulkAction({
    entity: 'teachers',
    title: 'Approve Selected Teachers?',
    text: 'In sabhi teachers ka profile active ho jayega.',
    confirmText: 'Yes, Approve',
    btnClass: 'btn-3d-success',
    url: "/admin/teachers/bulk-approve",
    msgKey: 'activated_success_msg'
});

const bulkTeacherActivate = () => executeBulkAction({
    entity: 'teachers',
    title: 'Re-activated Selected Teachers?',
    text: 'In sabhi teachers ka profile active ho jayega.',
    confirmText: 'Yes, Active',
    btnClass: 'btn-3d-primary',
    url: "/admin/teachers/bulk-activate",
    msgKey: 'activated_success_msg'
});

function bulkTeacherDelete(isPermanent = false) {
    executeBulkAction({
        entity: 'teachers',
        title: isPermanent ? 'Delete Selected?' : 'Make Inactive?',
        text: isPermanent ? 'Ye records hamesha ke liye delete ho jayenge!' : 'Ye teacher inactive list mein chale jayenge.',
        icon: 'warning',
        confirmText: isPermanent ? 'Yes, Delete' : 'Yes, Inactivate',
        btnClass: 'btn-3d-danger',
        method: isPermanent ? 'DELETE' : 'POST',
        url: isPermanent ? "/admin/teachers/bulk-delete" : "/admin/teachers/bulk-inactivate",
        msgKey: 'delete_success_msg'
    });
}

function approveteacher(button) {
    const $btn = $(button);
    const $form = $btn.closest('form');
    const isApprove = $btn.hasClass('btn-approve');

    Swal.fire({
        title: isApprove ? 'Approve Joining?' : 'Re-Activate Teacher?',
        text: "Kya aap is Teacher ko Active list mein move karna chahte hain?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes,Approve',
        customClass: { popup: 'card-morphism', confirmButton: 'btn-3d-success', cancelButton: 'btn-3d-secondary' }
    }).then((result) => {
        if (result.isConfirmed) {
            executeAjaxAction($form.attr('action'), { status: 1 }, { msgKey: 'activated_success_msg' }, $btn, $btn.html(), $form.closest('tr'));
        }
    });
}
const activateteacher = approveteacher;

function deleteTeacher(id, status, btn = null) {
    const $form = $('#delete-form-' + id);
    const isInactiveAction = (status == 1);

    Swal.fire({
        title: isInactiveAction ? 'Inactive karein?' : 'Delete karein?',
        text: isInactiveAction ? 'Teacher Inactive list mein chala jayega.' : 'Permanently delete ho jayega!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Proceed',
        customClass: { popup: 'card-morphism', confirmButton: 'btn-3d-danger', cancelButton: 'btn-3d-secondary' }
    }).then((result) => {
        if (result.isConfirmed) {
            executeAjaxAction($form.attr('action'), {}, { msgKey: 'delete_success_msg' }, $(btn), $(btn).html(), $form.closest('tr'), 'DELETE');
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const statsEl = document.getElementById('stats-data');
    if (!statsEl) return;
    const data = JSON.parse(statsEl.value);

    const getGradient = (ctx, colorStart, colorEnd) => {
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, colorStart);
        gradient.addColorStop(1, colorEnd);
        return gradient;
    };

    const render = (id, counts, isPie) => {
        const canvas = document.getElementById(id);
        const ctx = canvas.getContext('2d');

        const colors = [
            getGradient(ctx, 'rgba(0, 122, 255, 1)', 'rgba(0, 122, 255, 0.6)'),
            getGradient(ctx, 'rgba(52, 199, 89, 1)', 'rgba(52, 199, 89, 0.6)'), 
            getGradient(ctx, 'rgba(255, 59, 48, 1)', 'rgba(255, 59, 48, 0.6)'), 
            getGradient(ctx, 'rgba(88, 86, 214, 1)', 'rgba(88, 86, 214, 0.6)')   
        ];

        Chart.getChart(id)?.destroy();

        new Chart(ctx, {
            type: isPie ? 'doughnut' : 'bar',
            data: {
                labels: isPie ? ['Active', 'Inactive', 'Pending'] : ['Total', 'Active', 'Inactive', 'Pending'],
                datasets: [{
                    data: isPie ? [counts.active, counts.inactive, counts.pending] : [counts.total, counts.active, counts.inactive, counts.pending],
                    backgroundColor: colors,
                    borderColor: 'rgba(255, 255, 255, 0.8)',
                    borderWidth: 2,
                    borderRadius: isPie ? 0 : 12, 
                    cutout: isPie ? '70%' : null,
                    hoverOffset: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: isPie, 
                        position: 'bottom',
                        labels: { usePointStyle: true, font: { weight: 'bold' } }
                    },
                },
                scales: isPie ? {} : {
                    y: { display: false, beginAtZero: true, grid: { display: false } }, // Clean look
                    x: { grid: { display: false } }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart'
                }
            }
        });
    };

    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                const id = e.target.id;
                render(id, data[id.toLowerCase().includes('student') ? 'student' : 'teacher'], id.includes('Pie'));
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('canvas').forEach(c => obs.observe(c));
});

$(document).on('submit', '#addSubjectForm', function (e) {
    e.preventDefault();
    const $form = $(this);
    const $btn = $form.find('button[type="submit"]');
    const url = $form.attr('action');
    const formData = {};
    $form.serializeArray().forEach(item => {
        formData[item.name] = item.value;
    });
    executeAjaxAction(
        url,
        formData,
        { msgKey: 'update_success_msg' },
        $btn,
        $btn.html()
    );
});

const logoutConfirm = () => {
    Swal.fire({
        title: 'You Want To Logout ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Logout!',
        customClass: {
            popup: 'card-morphism border-0 shadow-lg',
            title: 'fw-bold text-danger',
            confirmButton: 'btn-3d-danger mx-2',
            cancelButton: 'btn-3d-secondary mx-2'
        }
    }).then(r => r.isConfirmed && document.getElementById('logout-form').submit());
};

window.addEventListener('pageshow', function (event) {
    if (event.persisted || (typeof window.performance != "undefined" && window.performance.navigation.type === 2)) {
        window.location.reload();
    }
});