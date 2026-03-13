<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Portal | SMS</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/teacher.css') }}">
</head>
<body>

    <div class="blob-container">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="dashboard-layout">
        
        <aside class="sidebar glass" id="sidebar">
            <i class="fa-solid fa-xmark close-sidebar" onclick="toggleSidebar()"></i>
            <div class="brand">
                <i class="fa-solid fa-chalkboard-user" style="color: var(--accent-color);"></i>
                <span>Staff Room</span>
            </div>
            
            <nav>
                <a href="#" class="nav-item active"><i class="fa-solid fa-layer-group"></i> Dashboard</a>
                <a href="#" class="nav-item"><i class="fa-solid fa-users"></i> My Students</a>
                <a href="#" class="nav-item"><i class="fa-solid fa-clipboard-check"></i> Attendance</a>
                <a href="#" class="nav-item"><i class="fa-solid fa-marker"></i> Marks Entry</a>
                <a href="#" class="nav-item"><i class="fa-solid fa-calendar-week"></i> Timetable</a>
            </nav>

            <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
                @csrf
                <button type="submit" class="nav-item logout-btn">
                    <i class="fa-solid fa-power-off"></i> Logout
                </button>
            </form>
        </aside>

        <main class="main-content">
            
            <header class="header glass" style="padding: 15px 25px; border-radius: 12px;">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <i class="fa-solid fa-bars menu-toggle" onclick="toggleSidebar()"></i>
                    <div>
                        <h2 style="margin: 0;">Welcome, {{ Auth::user()->name }} 👨‍🏫</h2>
                        <small style="color: var(--text-gray);">Senior Mathematics Teacher</small>
                    </div>
                </div>
                
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="position: relative;">
                        <i class="fa-regular fa-bell" style="font-size: 1.2rem; cursor: pointer;"></i>
                        <span style="position: absolute; top: -5px; right: -5px; background: var(--danger-color); width: 8px; height: 8px; border-radius: 50%;"></span>
                    </div>
                    <div style="width: 40px; height: 40px; background: var(--primary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </header>

            <div class="stats-grid">
                <div class="glass stat-card">
                    <div class="icon-box bg-indigo"><i class="fa-solid fa-user-graduate"></i></div>
                    <span style="color: var(--text-gray);">Total Students</span>
                    <h2 style="margin: 10px 0;">120</h2>
                    <small style="color: #818cf8;">Class 9th & 10th</small>
                </div>

                <div class="glass stat-card">
                    <div class="icon-box bg-teal"><i class="fa-solid fa-check-double"></i></div>
                    <span style="color: var(--text-gray);">Today's Attendance</span>
                    <h2 style="margin: 10px 0;">95%</h2>
                    <small style="color: #2dd4bf;">All classes combined</small>
                </div>

                <div class="glass stat-card">
                    <div class="icon-box bg-orange"><i class="fa-solid fa-book-open"></i></div>
                    <span style="color: var(--text-gray);">Pending Homework</span>
                    <h2 style="margin: 10px 0;">2 Sets</h2>
                    <small style="color: #fb923c;">To be checked</small>
                </div>
            </div>

            <div class="glass table-container">
                <h3 style="margin-bottom: 20px;">📅 Upcoming Classes Today</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>10th - A</strong></td>
                            <td>Mathematics</td>
                            <td>09:00 AM</td>
                            <td><span style="background: rgba(20, 184, 166, 0.2); color: #2dd4bf; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem;">Completed</span></td>
                            <td><button style="background: none; border: 1px solid var(--text-gray); color: white; padding: 5px 10px; border-radius: 6px; cursor: pointer;">View</button></td>
                        </tr>
                        <tr>
                            <td><strong>9th - B</strong></td>
                            <td>Physics</td>
                            <td>11:30 AM</td>
                            <td><span style="background: rgba(249, 115, 22, 0.2); color: #fb923c; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem;">Next</span></td>
                            <td><button style="background: var(--primary-color); border: none; color: white; padding: 5px 10px; border-radius: 6px; cursor: pointer;">Start</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>

</body>
</html>