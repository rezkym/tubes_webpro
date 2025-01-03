@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User Management</h5>
            @can('create', App\Models\User::class)
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New User
            </a>
            @endcan
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Info</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        @if($role->name == 'admin')
                                            <span class="badge bg-success">{{ $role->name }}</span>
                                        @elseif($role->name == 'teacher')
                                            <span class="badge bg-primary">{{ $role->name }}</span>
                                        @elseif($role->name == 'student')
                                            <span class="badge bg-warning">{{ $role->name }}</span>
                                        @else
                                            <span class="badge bg-info">{{ $role->name }}</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @if($user->hasRole('student') && $user->studentProfile)
                                        <small>
                                            ID: {{ $user->studentProfile->student_number }}<br>
                                            Class: {{ $user->studentProfile->schoolClass->name }}
                                        </small>
                                    @elseif($user->hasRole('teacher') && $user->teacherProfile)
                                        <small>
                                            Employee Number: {{ $user->teacherProfile->employee_number }}<br>
                                            Specialization: {{ $user->teacherProfile->specialization }} <br>
                                            Teach Class: {{ $user->teacherProfile->classes->pluck('name')->implode(', ') }}
                                        </small>
                                    @else
                                        No Display
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @can('view', $user)
                                            <a href="{{ route('users.show', $user) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('update', $user)
                                            <a href="{{ route('users.edit', $user) }}" 
                                               class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $user)
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $user->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + userId).submit();
            }
        })
    }
</script>
@endsection