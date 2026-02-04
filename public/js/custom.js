function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('show');
}

/* Auto close sidebar on link click (mobile only) */
document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('.sidebar a');
    const sidebar = document.querySelector('.sidebar');

    links.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('show');
            }
        });
    });
});
document.addEventListener('click', function (e) {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('.btn');

    if (
        window.innerWidth <= 768 &&
        sidebar.classList.contains('show') &&
        !sidebar.contains(e.target) &&
        !toggleBtn.contains(e.target)
    ) {
        sidebar.classList.remove('show');
    }
});
