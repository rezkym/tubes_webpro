<!-- resources/views/students/edit.blade.php -->
@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Edit Student Profile</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="student_number">Student Number</label>
                            <input type="text" 
                                   class="form-control @error('student_number') is-invalid @enderror" 
                                   id="student_number" 
                                   name="student_number" 
                                   value="{{ old('student_number', $student->student_number) }}" 
                                   required>
                            @error('student_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="school_class_id">Class</label>
                            <select class="form-control @error('school_class_id') is-invalid @enderror" 
                                    id="school_class_id" 
                                    name="school_class_id" 
                                    required>
                                <option value="">Select Class</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}" 
                                        {{ (old('school_class_id', $student->school_class_id) == $class->id) ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('school_class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Rest of the form fields similar to create.blade.php but with $student->field_name as value -->
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Update Student Profile</button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection