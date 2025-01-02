@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Attendance Records</h1>
        <div>
            <a href="{{ route('attendance.report') }}" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> View Reports
            </a>
            @can('create', App\Models\Attendance::class)
            <a href="{{ route('attendance.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Take Attendance
            </a>
            @endcan
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Attendance Records</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('attendance.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="class_id">Class</label>
                            <select class="form-control" id="class_id" name="class_id">
                                <option value="">All Classes</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" 
                                            {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="subject_id">Subject</label>
                            <select class="form-control" id="subject_id" name="subject_id">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" 
                                            {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Apply Filters
                        </button>
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Student</th>
                            <th>Status</th>
                            <th>Recorded By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->date->format('d/m/Y') }}</td>
                            <td>{{ $attendance->class->name }}</td>
                            <td>{{ $attendance->subject->name }}</td>
                            <td>{{ $attendance->student->full_name }}</td>
                            <td>
                                @switch($attendance->status)
                                    @case('present')
                                        <span class="badge bg-success">Present</span>
                                        @break
                                    @case('absent')
                                        <span class="badge bg-danger">Absent</span>
                                        @break
                                    @case('late')
                                        <span class="badge bg-warning text-dark">Late</span>
                                        @break
                                    @case('sick')
                                        <span class="badge bg-info">Sick</span>
                                        @break
                                    @case('permitted')
                                        <span class="badge bg-primary">Permitted</span>
                                        @break
                                @endswitch
                            </td>
                            <td>{{ $attendance->teacher->full_name }}</td>
                            <td>
                                @can('update', $attendance)
                                <a href="{{ route('attendance.edit', [
                                    'class' => $attendance->school_class_id,
                                    'subject' => $attendance->school_subject_id,
                                    'date' => $attendance->date->format('Y-m-d')
                                ]) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No attendance records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection