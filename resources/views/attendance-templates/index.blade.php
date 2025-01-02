@extends('layouts.main')

@section('title', 'Attendance Templates')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Attendance Templates</h1>
        <a href="{{ route('attendance-templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Template
        </a>
    </div>

    @include('partials.alerts')

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($templates as $template)
                            <tr>
                                <td>{{ $template->name }}</td>
                                <td>{{ $template->schoolClass->name }}</td>
                                <td>{{ $template->subject->name }}</td>
                                <td>{{ ucfirst($template->day) }}</td>
                                <td>{{ $template->start_time }} - {{ $template->end_time }}</td>
                                <td>
                                    <span class="badge {{ $template->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $template->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('attendance-templates.edit', $template) }}" 
                                           class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('attendance-templates.destroy', $template) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this template?');"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No templates found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $templates->links() }}
            </div>
        </div>
    </div>
</div>
@endsection