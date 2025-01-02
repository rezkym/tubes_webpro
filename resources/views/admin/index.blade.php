@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <!-- Statistik Utama -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Siswa</h6>
                    <h2 class="mb-0">{{ $statistics['total_students'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Guru</h6>
                    <h2 class="mb-0">{{ $statistics['total_teachers'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-title">Total Kelas</h6>
                    <h2 class="mb-0">{{ $statistics['total_classes'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="card-title">Tingkat Kehadiran</h6>
                    <h2 class="mb-0">
                        @if(isset($attendanceData['daily_attendance']->first()->present_count))
                            {{ number_format(($attendanceData['daily_attendance']->first()->present_count / 
                               $attendanceData['daily_attendance']->first()->total) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik dan Statistik Kehadiran -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Tren Kehadiran (30 Hari Terakhir)</h5>
                </div>
                <div class="card-body">
                    <canvas id="attendanceChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Kehadiran per Kelas</h5>
                </div>
                <div class="card-body">
                    <canvas id="classAttendanceChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Siswa Baru dan Aktivitas -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Siswa Baru Terdaftar</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Tanggal Daftar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statistics['recent_students'] as $student)
                                <tr>
                                    <td>{{ $student->user->name }}</td>
                                    <td>{{ $student->schoolClass->name }}</td>
                                    <td>{{ $student->created_at->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aktivitas Terkini</h5>
                </div>
                <div class="card-body">
                    <!-- Implementasi aktivitas terkini akan ditambahkan nanti -->
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grafik Tren Kehadiran
    const attendanceData = @json($attendanceData['daily_attendance']);
    new Chart(document.getElementById('attendanceChart'), {
        type: 'line',
        data: {
            labels: attendanceData.map(item => item.attendance_date),
            datasets: [{
                label: 'Tingkat Kehadiran (%)',
                data: attendanceData.map(item => (item.present_count / item.total) * 100),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // Grafik Kehadiran per Kelas
    const classData = @json($attendanceData['class_attendance']);
    new Chart(document.getElementById('classAttendanceChart'), {
        type: 'bar',
        data: {
            labels: classData.map(item => item.name),
            datasets: [{
                label: 'Tingkat Kehadiran (%)',
                data: classData.map(item => (item.present_count / item.total) * 100),
                backgroundColor: 'rgb(54, 162, 235)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
});
</script>
@endpush
@endsection