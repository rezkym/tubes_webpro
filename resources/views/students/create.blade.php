<!-- resources/views/students/create.blade.php -->
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
                            <input type="text" 
                                   class="form-control @error('student_number') is-invalid @enderror" 
                                   id="student_number" 
                                   name="student_number" 
                                   value="{{ old('student_number') }}" 
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
                                        {{ old('school_class_id') == $class->id ? 'selected' : '' }}>
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

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="full_name">Full Name</label>
                            <input type="text" 
                                   class="form-control @error('full_name') is-invalid @enderror" 
                                   id="full_name" 
                                   name="full_name" 
                                   value="{{ old('full_name') }}" 
                                   required>
                            @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" 
                                   class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   id="date_of_birth" 
                                   name="date_of_birth" 
                                   value="{{ old('date_of_birth') }}" 
                                   required>
                            @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional fields -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      required>{{ old('address') }}</textarea>
                            @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="parent_name">Parent Name</label>
                            <input type="text" 
                                   class="form-control @error('parent_name') is-invalid @enderror" 
                                   id="parent_name" 
                                   name="parent_name" 
                                   value="{{ old('parent_name') }}" 
                                   required>
                            @error('parent_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="parent_phone">Parent Phone</label>
                            <input type="text" 
                                   class="form-control @error('parent_phone') is-invalid @enderror" 
                                   id="parent_phone" 
                                   name="parent_phone" 
                                   value="{{ old('parent_phone') }}" 
                                   required>
                            @error('parent_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select class="form-control @error('gender') is-invalid @enderror" 
                                    id="gender" 
                                    name="gender" 
                                    required>
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="enrollment_date">Enrollment Date</label>
                            <input type="date" 
                                   class="form-control @error('enrollment_date') is-invalid @enderror" 
                                   id="enrollment_date" 
                                   name="enrollment_date" 
                                   value="{{ old('enrollment_date', date('Y-m-d')) }}" 
                                   required>
                            @error('enrollment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Create Student Profile</button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection