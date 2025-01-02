@extends('layouts.main')

@section('title', 'Teacher Dashboard')

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .schedule-item {
        border-left: 4px solid #4f46e5;
    }
    .attendance-badge {
        font-size: 0.8rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Welcome Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Welcome back, {{ Auth::user()->name }}!</h2>
        <div class="text-muted">{{ now()->format('l, F d, Y') }}</div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Today's Classes</h6>
                            <h3 class="mb-0">{{ $todaySchedule->count() }}</h3>
                        </div>
                        <i class="fas fa-calendar-day fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        @foreach($attendanceStats as $stat)
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">{{ ucfirst($stat->status) }}</h6>
                            <h3 class="mb-0">{{ $stat->count }}</h3>
                        </div>
                        <i class="fas fa-user-check fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Today's Schedule -->
    <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Today's Schedule</h5>
                </div>
                <div class="card-body">
                    @if($todaySchedule->count() > 0)
                        @foreach($todaySchedule as $schedule)
                        <div class="schedule-item p-3 mb-3 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $schedule->subject->name }}</h6>
                                    <p class="mb-0 text-muted">{{ $schedule->schoolClass->name }}</p>
                                </div>
                                <div class="text-end">
                                    <div class="mb-1">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</div>
                                    <a href="{{ route('attendance.create', ['class' => $schedule->school_class_id, 'subject' => $schedule->school_subject_id]) }}" 
                                       class="btn btn-sm btn-primary">Take Attendance</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">No classes scheduled for today.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Attendance -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Attendance Records</h5>
                </div>
                <div class="card-body">
                    @if($recentAttendance->count() > 0)
                        @foreach($recentAttendance as $record)
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $record->student->full_name }}</h6>
                                <p class="mb-0 text-muted">
                                    {{ $record->subject->name }} - {{ $record->class->name }}
                                </p>
                            </div>
                            <span class="badge bg-{{ $record->status === 'present' ? 'success' : 'danger' }} attendance-badge">
                                {{ ucfirst($record->status) }}
                            </span>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">No recent attendance records.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection