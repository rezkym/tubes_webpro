@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Student Profile Details</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th>Student Number:</th>
                            <td>{{ $student->student_number }}</td>
                        </tr>
                        <tr>
                            <th>Full Name:</th>
                            <td>{{ $student->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Class:</th>
                            <td>{{ $student->schoolClass->name }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth:</th>
                            <td>{{ $student->date_of_birth->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Gender:</th>
                            <td>{{ ucfirst($student->gender) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th>Parent Name:</th>
                            <td>{{ $student->parent_name }}</td>
                        </tr>
                        <tr>
                            <th>Parent Phone:</th>
                            <td>{{ $student->parent_phone }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $student->address }}</td>
                        </tr>
                        <tr>
                            <th>Enrollment Date:</th>
                            <td>{{ $student->enrollment_date->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to List</a>
                    @can('update', $student)
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">Edit Profile</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection