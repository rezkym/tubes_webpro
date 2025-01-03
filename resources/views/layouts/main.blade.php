<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar-link {
            color: #ffffff;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar-link:hover {
            background-color: #495057;
            color: #ffffff;
        }
        .sidebar-link.active {
            background-color: #495057;
        }
        .content {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="text-center p-4">
                    <h4 class="text-white">{{ config('app.name') }}</h4>
                </div>

                <nav class="mt-2">
                    @role('admin')
                        <a href="{{ route('admin.home') }}" class="sidebar-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a href="{{ route('school-classes.index') }}" class="sidebar-link {{ request()->routeIs('school-classes.*') ? 'active' : '' }}">
                            <i class="fas fa-chalkboard me-2"></i> Classes
                        </a>
                        <a href="{{ route('school-subjects.index') }}" class="sidebar-link {{ request()->routeIs('school-subjects.*') ? 'active' : '' }}">
                            <i class="fas fa-book me-2"></i> Subjects
                        </a>
                        <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="fas fa-users me-2"></i> Users
                        </a>
                        <a href="{{ route('attendance-templates.index') }}" class="sidebar-link {{ request()->routeIs('attendance-templates.*') ? 'active' : '' }}">
                            <i class="fas fa-clock me-2"></i> Class Schedule
                        </a>
                        {{-- <a href="{{ route('attendance.index') }}" class="sidebar-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check me-2"></i> Attendance
                        </a> --}}
                    @endrole

                    @role('teacher')
                        <a href="{{ route('teacher.home') }}" class="sidebar-link {{ request()->routeIs('teacher.home') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        {{-- <a href="{{ route('attendance.index') }}" class="sidebar-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check me-2"></i> Attendance
                        </a> --}}
                        <a href="{{ route('students.index') }}" class="sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                            <i class="fas fa-user-graduate me-2"></i> My Students
                        </a>
                        <a href="{{ route('attendance-templates.index') }}" class="sidebar-link {{ request()->routeIs('attendance-templates.*') ? 'active' : '' }}">
                            <i class="fas fa-clock me-2"></i> My Schedule
                        </a>
                    @endrole

                    @role('student')
                        <a href="{{ route('student.home') }}" class="sidebar-link {{ request()->routeIs('student.home') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    @endrole
                </nav>
            </div>

            <div class="col-md-9 col-lg-10 px-0 content">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        {{ now()->format('l, F j, Y: H:i:s') }}

                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-user me-2"></i> Profile
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <main class="py-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>