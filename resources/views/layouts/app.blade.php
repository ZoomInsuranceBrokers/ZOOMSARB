<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard') | Zoom SARB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap');
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(120deg, #f7faff 0%, #e0e7ef 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: linear-gradient(90deg, #1e3c72 0%, #ffb347 100%);
            color: #fff;
            padding: 0.8rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 10;
            box-shadow: 0 2px 8px #1e3c7244;
        }
        .navbar .brand {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: #fff;
            text-shadow: 0 2px 8px #ffb34744;
        }
        .navbar .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .gear-icon {
            font-size: 1.7rem;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .gear-icon:hover {
            transform: rotate(40deg) scale(1.1);
        }
        .profile-dropdown {
            position: absolute;
            top: 60px;
            right: 2rem;
            background: #fff;
            color: #1e3c72;
            border-radius: 10px;
            box-shadow: 0 4px 16px #1e3c7244;
            min-width: 180px;
            display: none;
            flex-direction: column;
            z-index: 100;
            animation: fadeIn 0.3s;
        }
        .profile-dropdown.show {
            display: flex;
        }
        .profile-dropdown a, .profile-dropdown form button {
            padding: 1rem 1.2rem;
            text-align: left;
            background: none;
            border: none;
            color: #1e3c72;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
            width: 100%;
        }
        .profile-dropdown a:hover, .profile-dropdown form button:hover {
            background: #f7faff;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .main-content {
            display: flex;
            flex: 1;
            min-height: 0;
        }
        .sidebar {
            width: 220px;
            background: linear-gradient(120deg, #1e3c72 0%, #ffb347 100%);
            color: #fff;
            padding: 2rem 1rem 1rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            min-height: 100%;
            box-shadow: 2px 0 8px #1e3c7244;
            animation: slideInLeft 0.7s;
        }
        .sidebar .sidebar-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            font-size: 1.05rem;
            padding: 0.7rem 1rem;
            border-radius: 8px;
            transition: background 0.2s, color 0.2s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #fff;
            color: #ffb347;
        }
        @keyframes slideInLeft {
            from { transform: translateX(-100px); opacity: 0;}
            to { transform: translateX(0); opacity: 1;}
        }
        .content-area {
            flex: 1;
            padding: 2.5rem 2rem 2rem 2rem;
            animation: fadeIn 1s;
            min-width: 0;
        }
        .footer {
            background: #1e3c72;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
            font-size: 1rem;
            letter-spacing: 1px;
            box-shadow: 0 -2px 8px #1e3c7244;
        }
        @media (max-width: 900px) {
            .sidebar {
                display: none;
            }
            .main-content {
                flex-direction: column;
            }
            .content-area {
                padding: 2rem 0.5rem 2rem 0.5rem;
            }
        }
    </style>
    <!-- Font Awesome for gear icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
     {{-- Success Modal --}}
    @if (session('success'))
        <div id="successModal" style="position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(46,49,146,0.15); z-index:9999; display:flex; align-items:center; justify-content:center;">
            <div style="background: #fff; border-radius: 18px; box-shadow: 0 8px 32px #2e319244; padding: 2.5rem 2rem; max-width: 350px; text-align:center; animation: popInModal 0.5s;">
                <div style="font-size:2.5rem; color:#1bffff; margin-bottom:1rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div style="font-size:1.2rem; color:#2e3192; font-weight:600; margin-bottom:1.2rem;">
                    {{ session('success') }}
                </div>
                <button onclick="document.getElementById('successModal').style.display='none';" style="background: linear-gradient(90deg, #2e3192 0%, #1bffff 100%); color: #fff; border: none; border-radius: 8px; padding: 0.7rem 2.2rem; font-size: 1.05rem; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px #2e319222; transition: background 0.2s;">
                    Close
                </button>
            </div>
        </div>
        <style>
            @keyframes popInModal {
                from { opacity: 0; transform: scale(0.8);}
                to { opacity: 1; transform: scale(1);}
            }
        </style>
        <script>
            setTimeout(function() {
                var modal = document.getElementById('successModal');
                if(modal) modal.style.display = 'none';
            }, 3000);
        </script>
    @endif

    {{-- Error Modal --}}
    @if (session('error'))
        <div id="errorModal" style="position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(46,49,146,0.15); z-index:9999; display:flex; align-items:center; justify-content:center;">
            <div style="background: #fff; border-radius: 18px; box-shadow: 0 8px 32px #d32f2f44; padding: 2.5rem 2rem; max-width: 350px; text-align:center; animation: popInModal 0.5s;">
                <div style="font-size:2.5rem; color:#d32f2f; margin-bottom:1rem;">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div style="font-size:1.2rem; color:#d32f2f; font-weight:600; margin-bottom:1.2rem;">
                    {{ session('error') }}
                </div>
                <button onclick="document.getElementById('errorModal').style.display='none';" style="background: linear-gradient(90deg, #d32f2f 0%, #ffbdbd 100%); color: #fff; border: none; border-radius: 8px; padding: 0.7rem 2.2rem; font-size: 1.05rem; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px #d32f2f22; transition: background 0.2s;">
                    Close
                </button>
            </div>
        </div>
        <style>
            @keyframes popInModal {
                from { opacity: 0; transform: scale(0.8);}
                to { opacity: 1; transform: scale(1);}
            }
        </style>
        <script>
            setTimeout(function() {
                var modal = document.getElementById('errorModal');
                if(modal) modal.style.display = 'none';
            }, 3000);
        </script>
    @endif
    <div class="navbar">
        <div class="brand">ZOOM SARB</div>
        <div class="nav-actions">
            <span class="gear-icon" id="gearIcon"><i class="fas fa-cog"></i></span>
            <div class="profile-dropdown" id="profileDropdown">
                <a href="#">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="sidebar">
            <div class="sidebar-title">Menu</div>
            <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a>
            <a href="#"><i class="fas fa-users"></i> Users</a>
            <a href="{{ url('/quotes/list') }}" class="{{ request()->is('quotes.list') ? 'active' : '' }}"><i class="fas fa-chart-bar"></i> Quotes</a>
            <a href="#"><i class="fas fa-cogs"></i> Reports</a>
        </div>
        <div class="content-area">
            @yield('content')
        </div>
    </div>
    <div class="footer">
        &copy; {{ date('Y') }} Zoom SARB. All rights reserved.
    </div>
    <script>
        // Toggle profile dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const gearIcon = document.getElementById('gearIcon');
            const dropdown = document.getElementById('profileDropdown');
            document.addEventListener('click', function(e) {
                if (gearIcon.contains(e.target)) {
                    dropdown.classList.toggle('show');
                } else if (!dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>
