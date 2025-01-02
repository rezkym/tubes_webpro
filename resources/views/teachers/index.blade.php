@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Teacher Profiles</h1>
        @can('create', App\Models\TeacherProfile::class)
        <a href="{{ route('teachers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Teacher
        </a>
        @endcan
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Employee Number</th>
                            <th>Full Name</th>
                            <th>Specialization</th>
                            <th>Subjects</th>
                            <th>Classes</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->employee_number }}</td>
                            <td>{{ $teacher->full_name }}</td>
                            <td>{{ $teacher->specialization }}</td>
                            <td>
                                @foreach($teacher->subjects as $subject)
                                    <span class="badge bg-primary">{{ $subject->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @foreach($teacher->classes as $class)
                                    <span class="badge bg-info">{{ $class->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('teachers.show', $teacher) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('update', $teacher)
                                    <a href="{{ route('teachers.edit', $teacher) }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('delete', $teacher)
                                    <form action="{{ route('teachers.destroy', $teacher) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $teachers->links() }}
        </div>
    </div>
</div>
@endsection