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
                            {{ (old('role', isset($user) ? $user->roles->first()->name : '') == $role->name) ? 'selected' : '' }}>
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
                    name="class_id"
                    required>
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
                   value="{{ old('student_number', $user->studentProfile->student_number ?? '') }}"
                   required>
            @error('student_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="full_name" class="form-label">Full Name</label>
            <input type="text" 
                   class="form-control @error('full_name') is-invalid @enderror" 
                   id="full_name" 
                   name="full_name" 
                   value="{{ old('full_name', $user->studentProfile->full_name ?? '') }}"
                   maxlength="255"
                   required>
            @error('full_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="date_of_birth" class="form-label">Date of Birth</label>
            <input type="date" 
                   class="form-control @error('date_of_birth') is-invalid @enderror" 
                   id="date_of_birth" 
                   name="date_of_birth" 
                   value="{{ old('date_of_birth', $user->studentProfile->date_of_birth ?? '') }}"
                   required>
            @error('date_of_birth')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select class="form-select @error('gender') is-invalid @enderror" 
                    id="gender" 
                    name="gender"
                    required>
                <option value="">Select Gender</option>
                <option value="male" {{ (old('gender', $user->studentProfile->gender ?? '') == 'male') ? 'selected' : '' }}>Male</option>
                <option value="female" {{ (old('gender', $user->studentProfile->gender ?? '') == 'female') ? 'selected' : '' }}>Female</option>
            </select>
            @error('gender')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control @error('address') is-invalid @enderror" 
                      id="address" 
                      name="address" 
                      required>{{ old('address', $user->studentProfile->address ?? '') }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="parent_name" class="form-label">Parent Name</label>
            <input type="text" 
                   class="form-control @error('parent_name') is-invalid @enderror" 
                   id="parent_name" 
                   name="parent_name" 
                   value="{{ old('parent_name', $user->studentProfile->parent_name ?? '') }}"
                   maxlength="255"
                   required>
            @error('parent_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="parent_phone" class="form-label">Parent Phone</label>
            <input type="text" 
                   class="form-control @error('parent_phone') is-invalid @enderror" 
                   id="parent_phone" 
                   name="parent_phone" 
                   value="{{ old('parent_phone', $user->studentProfile->parent_phone ?? '') }}"
                   maxlength="255"
                   required>
            @error('parent_phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="enrollment_date" class="form-label">Enrollment Date</label>
            <input type="date" 
                   class="form-control @error('enrollment_date') is-invalid @enderror" 
                   id="enrollment_date" 
                   name="enrollment_date" 
                   value="{{ old('enrollment_date', $user->studentProfile->enrollment_date ?? '') }}"
                   required>
            @error('enrollment_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6" id="teacherFields" style="display: none;">
        <!-- Teacher Profile Fields -->
        <div class="mb-3">
            <label for="employee_number" class="form-label">Employee Number</label>
            <input type="text" 
                   class="form-control @error('employee_number') is-invalid @enderror" 
                   id="employee_number" 
                   name="employee_number" 
                   value="{{ old('employee_number', $user->teacherProfile->employee_number ?? '') }}"
                   required>
            @error('employee_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="mb-3">
            <label for="teacher_full_name" class="form-label">Full Name</label>
            <input type="text" 
                   class="form-control @error('teacher_full_name') is-invalid @enderror" 
                   id="teacher_full_name" 
                   name="teacher_full_name" 
                   value="{{ old('teacher_full_name', $user->teacherProfile->full_name ?? '') }}"
                   required>
            @error('teacher_full_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="mb-3">
            <label for="specialization" class="form-label">Specialization</label>
            <input type="text" 
                   class="form-control @error('specialization') is-invalid @enderror" 
                   id="specialization" 
                   name="specialization" 
                   value="{{ old('specialization', $user->teacherProfile->specialization ?? '') }}"
                   required>
            @error('specialization')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number</label>
            <input type="text" 
                   class="form-control @error('phone_number') is-invalid @enderror" 
                   id="phone_number" 
                   name="phone_number" 
                   value="{{ old('phone_number', $user->teacherProfile->phone_number ?? '') }}"
                   required>
            @error('phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="mb-3">
            <label for="teacher_address" class="form-label">Address</label>
            <textarea class="form-control @error('teacher_address') is-invalid @enderror" 
                      id="teacher_address" 
                      name="teacher_address" 
                      required>{{ old('teacher_address', $user->teacherProfile->address ?? '') }}</textarea>
            @error('teacher_address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="mb-3">
            <label for="join_date" class="form-label">Join Date</label>
            <input type="date" 
                   class="form-control @error('join_date') is-invalid @enderror" 
                   id="join_date" 
                   name="join_date" 
                   value="{{ old('join_date', $user->teacherProfile->join_date ?? '') }}"
                   required>
            @error('join_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="mb-3">
            <label for="education_level" class="form-label">Education Level</label>
            <select class="form-select @error('education_level') is-invalid @enderror" 
                    id="education_level" 
                    name="education_level"
                    required>
                <option value="">Select Education Level</option>
                <option value="Bachelor" {{ (old('education_level', $user->teacherProfile->education_level ?? '') == 'Bachelor') ? 'selected' : '' }}>Bachelor's Degree</option>
                <option value="Master" {{ (old('education_level', $user->teacherProfile->education_level ?? '') == 'Master') ? 'selected' : '' }}>Master's Degree</option>
                <option value="Doctorate" {{ (old('education_level', $user->teacherProfile->education_level ?? '') == 'Doctorate') ? 'selected' : '' }}>Doctorate</option>
            </select>
            @error('education_level')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="mb-3">
            <label for="teaching_experience_years" class="form-label">Teaching Experience (Years)</label>
            <input type="number" 
                   class="form-control @error('teaching_experience_years') is-invalid @enderror" 
                   id="teaching_experience_years" 
                   name="teaching_experience_years" 
                   value="{{ old('teaching_experience_years', $user->teacherProfile->teaching_experience_years ?? '') }}"
                   min="0"
                   required>
            @error('teaching_experience_years')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        const studentFields = document.getElementById('studentFields');
        const teacherFields = document.getElementById('teacherFields');
        
        function toggleFields() {
            const selectedRole = roleSelect.value;
            
            // Handle student fields
            studentFields.style.display = selectedRole === 'student' ? 'block' : 'none';
            studentFields.querySelectorAll('input, select, textarea').forEach(input => {
                input.required = selectedRole === 'student';
            });
            
            // Handle teacher fields
            teacherFields.style.display = selectedRole === 'teacher' ? 'block' : 'none';
            teacherFields.querySelectorAll('input, select, textarea').forEach(input => {
                input.required = selectedRole === 'teacher';
            });
        }
    
        roleSelect.addEventListener('change', toggleFields);
        toggleFields(); // Set initial state
    });
</script>
@endpush