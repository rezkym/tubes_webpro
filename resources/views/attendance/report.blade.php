@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Attendance Reports</h1>
        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Records
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Generate Attendance Report</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('attendance.report') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ request('start_date') }}" 
                                   required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ request('end_date') }}" 
                                   required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="class_id">Class</label>
                            <select class="form-control" id="class_id" name="class_id" required>
                                <option value="">Select Class</option>
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
                            <select class="form-control" id="subject_id" name="subject_id" required>
                                <option value="">Select Subject</option>
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
                            <i class="fas fa-search"></i> Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(request()->has(['start_date', 'end_date', 'class_id', 'subject_id']))
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Attendance Summary</h6>
                <button onclick="window.print()" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-print"></i> Print Report
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Total Days</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Late</th>
                                <th>Attendance Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendanceData as $data)
                            <tr>
                                <td>{{ $data['student']->full_name }}</td>
                                <td>{{ $data['total'] }}</td>
                                <td>{{ $data['present'] }}</td>
                                <td>{{ $data['absent'] }}</td>
                                <td>{{ $data['late'] }}</td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-{{ $data['attendance_rate'] >= 75 ? 'success' : ($data['attendance_rate'] >= 50 ? 'warning' : 'danger') }}" 
                                             role="progressbar" 
                                             style="width: {{ $data['attendance_rate'] }}%" 
                                             aria-valuenow="{{ $data['attendance_rate'] }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ $data['attendance_rate'] }}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No attendance data found for the selected criteria.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
@media print {
    .no-print {
        display: none !important;
    }
    .card {
        border: none !important;
    }
    .progress {
        border: 1px solid #ddd;
    }
    .progress-bar {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}
</style>
@endpush
@endsection