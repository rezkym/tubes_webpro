@extends('layouts.main')

@section('title', 'Create Subject')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="mb-0">Create New Subject</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('school-subjects.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subject Code</label>
                                <input type="text" 
                                       name="code" 
                                       class="form-control @error('code') is-invalid @enderror"
                                       value="{{ old('code') }}" 
                                       required>
                                @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Subject Name</label>
                                <input type="text" 
                                       name="name" 
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Teacher</label>
                            <select name="teacher_id" 
                                    class="form-select @error('teacher_id') is-invalid @enderror"
                                    required>
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" 
                                            {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Assign to Classes</label>
                            <select name="classes[]" 
                                    class="form-select @error('classes') is-invalid @enderror"
                                    multiple>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" 
                                            {{ (old('classes') && in_array($class->id, old('classes'))) ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classes')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple classes</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" 
                                     class="form-control @error('description') is-invalid @enderror"
                                     rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1" 
                                       checked>
                                <label class="form-check-label" for="is_active">
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('school-subjects.index') }}" class="btn btn-secondary">
                                Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Create Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection