@extends('layouts.main')

@section('title', 'Manage Subjects')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="mt-4">School Subjects</h1>
        <a href="{{ route('school-subjects.create') }}" class="btn btn-primary">
            Create New Subject
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mt-4">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Teacher</th>
                        <th>Classes</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td>{{ $subject->code }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>{{ $subject->teacher->name }}</td>
                            <td>
                                @foreach($subject->classes as $class)
                                    <span class="badge bg-info">{{ $class->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-{{ $subject->is_active ? 'success' : 'danger' }}">
                                    {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('school-subjects.edit', $subject) }}" 
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>
                                    <form action="{{ route('school-subjects.destroy', $subject) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Are you sure you want to delete this subject?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No subjects found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $subjects->links() }}
            </div>
        </div>
    </div>
</div>
@endsection