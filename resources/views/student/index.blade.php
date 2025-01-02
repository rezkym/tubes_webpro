@extends('layouts.main')

@section('title', 'Student Dashboard')

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
    .attendance-history-item {
        border-bottom: 1px solid #eee;
    }
    .attendance-history-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Welcome Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Welcome, {{ Auth::user()->name }}!</h2>
        <div class="text-muted">{{ now()->format('l, F d, Y') }}</div>
    </div>

    <!-- Attendance Stats -->
    <div class="row mb-4">
        @foreach($attendanceStats as $stat)
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted">{{ ucfirst($stat->status) }}</h6>
                            <h3 class="mb-0">{{ $stat->count }}</h3>
                        </div>
                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <!-- Today's Schedule -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Today's Schedule</h5>
                </div>
                <div class="card-body">
                    @if($todaySchedule->first()['message'] ?? false)
                        <p class="text-muted mb-0">{{ $todaySchedule->first()['message'] }}</p>
                    @else
                        @foreach($todaySchedule as $schedule)
                        <div class="schedule-item p-3 mb-3 bg-light rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $schedule->subject->name }}</h6>
                                    <p class="mb-0 text-muted">
                                        Teacher: {{ $schedule->teacher->full_name }}
                                    </p>
                                </div>
                                <div class="text-end">
                                    <div class="mb-1">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </div>
                                    <span class="badge bg-{{ Carbon\Carbon::now()->between(
                                        Carbon\Carbon::parse($schedule->start_time), 
                                        Carbon\Carbon::parse($schedule->end_time)
                                    ) ? 'success' : 'secondary' }}">
                                        {{ Carbon\Carbon::now()->between(
                                            Carbon\Carbon::parse($schedule->start_time), 
                                            Carbon\Carbon::parse($schedule->end_time)
                                        ) ? 'Ongoing' : 'Upcoming' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Attendance History -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Attendance History</h5>
                </div>
                <div class="card-body">
                    @if($attendanceHistory->count() > 0)
                        @foreach($attendanceHistory as $record)
                        <div class="attendance-history-item py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $record->subject->name }}</h6>
                                    <small class="text-muted">
                                        {{ $record->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $record->status === 'present' ? 'success' : 
                                    ($record->status === 'late' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">No attendance records found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection