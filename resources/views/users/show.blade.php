@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User Details</h5>
            <div>
                @can('update', $user)
                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endcan
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="border-bottom pb-2">Basic Information</h6>
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-info">{{ $role->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>

                @if($user->hasRole('student') && $user->studentProfile)
                <div class="col-md-6">
                    <h6 class="border-bottom pb-2">Student Information</h6>
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Student Number</th>
                            <td>{{ $user->studentProfile->student_number }}</td>
                        </tr>
                        <tr>
                            <th>Full Name</th>
                            <td>{{ $user->studentProfile->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Class</th>
                            <td>{{ $user->studentProfile->schoolClass->name }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ ucfirst($user->studentProfile->gender) }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>{{ $user->studentProfile->date_of_birth->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $user->studentProfile->address }}</td>
                        </tr>
                        <tr>
                            <th>Parent Name</th>
                            <td>{{ $user->studentProfile->parent_name }}</td>
                        </tr>
                        <tr>
                            <th>Parent Phone</th>
                            <td>{{ $user->studentProfile->parent_phone }}</td>
                        </tr>
                        <tr>
                            <th>Enrollment Date</th>
                            <td>{{ $user->studentProfile->enrollment_date->format('d F Y') }}</td>
                        </tr>
                    </table>
                </div>
                @endif

                @if($user->hasRole('teacher') && $user->teacherProfile)
                <div class="col-md-6">
                    <h6 class="border-bottom pb-2">Teacher Information</h6>
                    <table class="table table-borderless">
                        <tr>
                            <th width="150">Employee Number</th>
                            <td>{{ $user->teacherProfile->employee_number }}</td>
                        </tr>
                        <tr>
                            <th>Full Name</th>
                            <td>{{ $user->teacherProfile->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Specialization</th>
                            <td>{{ $user->teacherProfile->specialization }}</td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td>{{ $user->teacherProfile->phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{ $user->teacherProfile->address }}</td>
                        </tr>
                        <tr>
                            <th>Join Date</th>
                            <td>{{ $user->teacherProfile->join_date->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Education Level</th>
                            <td>{{ $user->teacherProfile->education_level }}</td>
                        </tr>
                        <tr>
                            <th>Teaching Experience (Years)</th>
                            <td>{{ $user->teacherProfile->teaching_experience_years }}</td>
                        </tr>
                        <tr>
                            <th>Assigned Classes</th>
                            <td>
                                @foreach($user->teacherProfile->classes as $class)
                                    <span class="badge bg-secondary">{{ $class->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection