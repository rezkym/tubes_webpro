@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Create New Student Profile</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('students.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="student_number">Student Number</label>
                                <select class="form-control select2 @error('student_number') is-invalid @enderror"
                                    id="student_number" name="student_number" data-placeholder="Search student number..."
                                    required>
                                    @if (old('student_number'))
                                        <option value="{{ old('student_number') }}" selected>
                                            {{ old('student_number') }}
                                        </option>
                                    @endif
                                </select>
                                @error('student_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Rest of your existing form remains the same -->
                    </div>
                    <!-- ... other form fields ... -->
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
@endpush


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#student_number').select2({
                theme: 'bootstrap-5',
                ajax: {
                    url: '{{ route('api.student-numbers.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term,
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return data;
                    },
                    cache: true
                },
                minimumInputLength: 2,
                placeholder: 'Search by student number, name, or class',
                allowClear: true,
                templateResult: formatStudent,
                templateSelection: formatStudentSelection
            });
        });

        function formatStudent(student) {
            if (student.loading) {
                return student.text;
            }

            if (!student.text) {
                return null;
            }

            // Parse the combined string back into components
            const parts = student.text.match(/^(.+?) - (.+?) \((.+?)\)$/);
            if (!parts) return student.text;

            const [, studentNumber, name, className] = parts;

            return $(`
        <div class='select2-result-student'>
            <div class='select2-result-student__number'>${studentNumber}</div>
            <div class='select2-result-student__name'>${name}</div>
            <div class='select2-result-student__class'><small class="text-muted">Class: ${className}</small></div>
        </div>
    `);
        }

        function formatStudentSelection(student) {
            if (!student.text) return student.text;

            const parts = student.text.match(/^(.+?) - (.+?) \((.+?)\)$/);
            if (!parts) return student.text;

            const [, studentNumber, name] = parts;
            return `${studentNumber} - ${name}`;
        }
    </script>
@endpush

@push('styles')
    <style>
        .select2-result-student {
            padding: 4px;
        }

        .select2-result-student__number {
            font-weight: bold;
        }

        .select2-result-student__name {
            color: #333;
        }

        .select2-result-student__class {
            color: #666;
            font-size: 0.85em;
            margin-top: 2px;
        }
    </style>
@endpush
