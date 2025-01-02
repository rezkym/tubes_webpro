<!-- resources/views/attendance/edit.blade.php -->
@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Attendance</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Edit Attendance for {{ $class->name }} - {{ $subject->name }} ({{ \Carbon\Carbon::parse($date)->format('d/m/Y') }})
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('attendance.update', ['class' => $class->id, 'subject' => $subject->id, 'date' => $date]) }}" 
                  method="POST">
                @csrf
                @method('PUT')

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                            <tr>
                                <td>{{ $student->full_name }}</td>
                                <td>
                                    <select class="form-control" 
                                            name="attendances[{{ $student->id }}][status]" 
                                            required>
                                        <option value="">Select Status</option>
                                        @foreach(App\Models\Attendance::getStatusOptions() as $value => $label)
                                            <option value="{{ $value }}" 
                                                    {{ optional($attendances->get($student->id))->status === $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" 
                                           class="form-control" 
                                           name="attendances[{{ $student->id }}][remarks]" 
                                           value="{{ optional($attendances->get($student->id))->remarks }}"
                                           placeholder="Optional remarks">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Attendance
                        </button>
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
