@extends('layouts.main')

@section('title', 'Edit Subject')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Subject</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('school-subjects.update', $schoolSubject->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $schoolSubject->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="code" class="form-label">Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                           id="code" name="code" value="{{ old('code', $schoolSubject->code) }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description', $schoolSubject->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="teacher_id" class="form-label">Teacher</label>
                    <select class="form-select @error('teacher_id') is-invalid @enderror" 
                            id="teacher_id" name="teacher_id" required>
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" 
                                {{ old('teacher_id', $schoolSubject->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('teacher_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="classes" class="form-label">Classes</label>
                    <select class="form-select @error('classes') is-invalid @enderror" 
                            id="classes" name="classes[]" multiple>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ in_array($class->id, $selectedClasses) ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('classes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" 
                               id="is_active" name="is_active" value="1" 
                               {{ old('is_active', $schoolSubject->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('school-subjects.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-primary">Update Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection