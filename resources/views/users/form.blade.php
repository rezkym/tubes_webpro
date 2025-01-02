<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', $user->name ?? '') }}" 
                   required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email', $user->email ?? '') }}" 
                   required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">
                Password {{ isset($user) ? '(Leave blank to keep current password)' : '' }}
            </label>
            <input type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   id="password" 
                   name="password" 
                   {{ !isset($user) ? 'required' : '' }}>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" 
                   class="form-control" 
                   id="password_confirmation" 
                   name="password_confirmation" 
                   {{ !isset($user) ? 'required' : '' }}>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select @error('role') is-invalid @enderror" 
                    id="role" 
                    name="role" 
                    required>
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" 
                            {{ (old('role', $user->roles->first()->name ?? '') == $role->name) ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6" id="studentFields" style="display: none;">
        <!-- Student Profile Fields -->
        <div class="mb-3">
            <label for="class_id" class="form-label">Class</label>
            <select class="form-select @error('class_id') is-invalid @enderror" 
                    id="class_id" 
                    name="class_id">
                <option value="">Select Class</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" 
                            {{ (old('class_id', $user->studentProfile->school_class_id ?? '') == $class->id) ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
            @error('class_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="student_number" class="form-label">Student Number</label>
            <input type="text" 
                   class="form-control @error('student_number') is-invalid @enderror" 
                   id="student_number" 
                   name="student_number" 
                   value="{{ old('student_number', $user->studentProfile->student_number ?? '') }}">
            @error('student_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Add other student profile fields here -->
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const studentFields = document.getElementById('studentFields');

    function toggleStudentFields() {
        studentFields.style.display = roleSelect.value === 'student' ? 'block' : 'none';
    }

    roleSelect.addEventListener('change', toggleStudentFields);
    toggleStudentFields(); // Initial state
});
</script>
@endpush