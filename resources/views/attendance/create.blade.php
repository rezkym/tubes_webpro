@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Take Attendance</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Record Daily Attendance</h6>
        </div>
        <div class="card-body">
            <form id="attendanceForm" action="{{ route('attendance.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" 
                                   class="form-control @error('date') is-invalid @enderror" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', date('Y-m-d')) }}" 
                                   required>
                            @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="class_id">Class</label>
                            <select class="form-control @error('class_id') is-invalid @enderror" 
                                    id="class_id" 
                                    name="class_id" 
                                    required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" 
                                            {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="subject_id">Subject</label>
                            <select class="form-control @error('subject_id') is-invalid @enderror" 
                                    id="subject_id" 
                                    name="subject_id" 
                                    required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" 
                                            {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div id="studentList" class="d-none">
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
                                <!-- Students will be loaded here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary d-none" id="submitBtn">
                            <i class="fas fa-save"></i> Save Attendance
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('class_id');
    const subjectSelect = document.getElementById('subject_id');
    const dateInput = document.getElementById('date');
    const studentList = document.getElementById('studentList');
    const submitBtn = document.getElementById('submitBtn');
    const statusOptions = {
        present: 'Present',
        absent: 'Absent',
        late: 'Late',
        sick: 'Sick',
        permitted: 'Permitted'
    };

    async function loadStudents() {
        const classId = classSelect.value;
        const subjectId = subjectSelect.value;
        const date = dateInput.value;

        if (!classId || !subjectId || !date) return;

        try {
            const response = await fetch(`/attendance/get-students?class_id=${classId}&subject_id=${subjectId}`);
            const students = await response.json();

            const tbody = studentList.querySelector('tbody');
            tbody.innerHTML = '';

            students.forEach(student => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${student.full_name}</td>
                    <td>
                        <select class="form-control" 
                                name="attendances[${student.id}][status]" 
                                required>
                            <option value="">Select Status</option>
                            ${Object.entries(statusOptions).map(([value, label]) => 
                                `<option value="${value}">${label}</option>`
                            ).join('')}
                        </select>
                    </td>
                    <td>
                        <input type="text" 
                               class="form-control" 
                               name="attendances[${student.id}][remarks]" 
                               placeholder="Optional remarks">
                    </td>
                `;
                tbody.appendChild(tr);
            });

            studentList.classList.remove('d-none');
            submitBtn.classList.remove('d-none');
        } catch (error) {
            console.error('Error loading students:', error);
        }
    }

    classSelect.addEventListener('change', loadStudents);
    subjectSelect.addEventListener('change', loadStudents);
    dateInput.addEventListener('change', loadStudents);
});
</script>
@endpush
@endsection