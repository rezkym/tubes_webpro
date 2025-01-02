<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
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
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="text-center p-4">
                    <h4 class="text-white">{{ config('app.name') }}</h4>
                </div>

                <nav class="mt-2">
                    @role('admin')
                        <a href="{{ route('dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>

                        <a href="{{ route('school-classes.index') }}"
                            class="sidebar-link {{ request()->routeIs('school-classes.*') ? 'active' : '' }}">
                            <i class="fas fa-chalkboard me-2"></i> Classes
                        </a>

                        <a href="{{ route('school-subjects.index') }}" class="sidebar-link">
                            <i class="fas fa-book me-2"></i> Subjects
                        </a>

                        <a href="{{ route('users.index') }}" class="sidebar-link">
                            <i class="fas fa-users me-2"></i> Users
                        </a>
                    @endrole

                    @role('teacher')
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-calendar-check me-2"></i> Attendance
                        </a>

                        <a href="#" class="sidebar-link">
                            <i class="fas fa-clipboard-list me-2"></i> My Classes
                        </a>
                    @endrole

                    @role('student')
                        <a href="#" class="sidebar-link">
                            <i class="fas fa-calendar me-2"></i> My Attendance
                        </a>

                        <a href="#" class="sidebar-link">
                            <i class="fas fa-book-reader me-2"></i> My Subjects
                        </a>
                    @endrole

                    @canany(['viewAny', 'create'], App\Models\StudentProfile::class)
                        <a href="{{ route('students.index') }}"
                            class="sidebar-link {{ request()->routeIs('students.*') ? 'active' : '' }}">
                            <i class="fas fa-user-graduate me-2"></i> Student Management
                        </a>
                    @endcanany

                    @can('viewAny', App\Models\TeacherProfile::class)
                        <a href="{{ route('teachers.index') }}"
                            class="sidebar-link {{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                            <i class="fas fa-chalkboard-teacher me-2"></i> Teacher Management
                        </a>
                    @endcan

                    @can('viewAny', App\Models\Attendance::class)
                        <a href="{{ route('attendance.index') }}" 
                            class="sidebar-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check me-2"></i> Attendance
                        </a>
                    @endcan

                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 px-0 content">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <i class="fas fa-user me-2"></i> Profile
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
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

                <!-- Page Content -->
                <main class="py-4">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custome Script -->
    @yield('scripts')
</body>

</html>
