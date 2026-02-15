<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Student Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/student.css') }}">
</head>
<body>

    <div class="blob-container">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <div class="dashboard-container">
        
        <aside class="sidebar glass" id="sidebar">
            
            <i class="fa-solid fa-xmark close-sidebar" onclick="toggleSidebar()"></i>

            <div class="logo-area">
                <i class="fa-solid fa-school-flag fa-2x" style="color: #60a5fa;"></i>
                <h2 style="margin-top: 10px;">SMS 2026</h2>
            </div>
            
            <nav class="nav-links">
                <a href="#" class="active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
                <a href="#"><i class="fa-solid fa-book-open-reader"></i> My Subjects</a>
                <a href="#"><i class="fa-solid fa-trophy"></i> Results</a>
                <a href="#"><i class="fa-solid fa-calendar-days"></i> Timetable</a>
            </nav>

            <div class="logout-section">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-right-from-bracket" style="margin-right: 10px;"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            
            <header class="header glass">
                
                <i class="fa-solid fa-bars menu-toggle" onclick="toggleSidebar()"></i>

                <div style="flex: 1;"> <h1>Hello, {{ Auth::user()->name }}! 👋</h1>
                    <p style="color: var(--text-gray); font-size: 0.9rem;">Class 10th-A | Roll No: 24</p>
                </div>
                
                <div style="display: flex; align-items: center; gap: 15px;">
                    <i class="fa-solid fa-bell fa-lg" style="cursor: pointer;"></i>
                    <div style="width: 40px; height: 40px; background: linear-gradient(45deg, #3b82f6, #8b5cf6); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <div class="stats-grid">
                <div class="glass glass-hover p-4" style="padding: 25px;">
                    <div class="card-icon bg-green-glow"><i class="fa-solid fa-clipboard-check"></i></div>
                    <span style="color: var(--text-gray);">Attendance</span>
                    <h2 style="font-size: 2rem; margin: 10px 0;">85%</h2>
                    <div style="height: 5px; background: rgba(255,255,255,0.1); border-radius: 5px;">
                        <div style="height: 100%; width: 85%; background: #4ade80; border-radius: 5px;"></div>
                    </div>
                </div>
                </div>

            <div class="glass table-container">
                <h3 style="margin-bottom: 20px;"><i class="fa-solid fa-clock" style="color: #60a5fa; margin-right: 10px;"></i> Today's Schedule</h3>
                
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="color: #60a5fa;">09:00 AM</td>
                            <td>Mathematics</td>
                            <td>Mr. Sharma</td>
                            <td><span style="background: rgba(34, 197, 94, 0.2); color: #4ade80; padding: 5px 10px; border-radius: 20px; font-size: 0.8rem;">Done</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active'); // Class add/remove karega
        }
    </script>

</body>
</html>