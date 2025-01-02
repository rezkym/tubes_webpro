@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Teacher Profile Details</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <th>Employee Number:</th>
                                <td>{{ $teacher->employee_number }}</td>
                            </tr>
                            <tr>
                                <th>Full Name:</th>
                                <td>{{ $teacher->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Specialization:</th>
                                <td>{{ $teacher->specialization }}</td>
                            </tr>
                            <tr>
                                <th>Phone Number:</th>
                                <td>{{ $teacher->phone_number }}</td>
                            </tr>
                            <tr>
                                <th>Education Level:</th>
                                <td>{{ $teacher->education_level }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table">
                            <tr>
                                <th>Join Date:</th>
                                <td>{{ $teacher->join_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Teaching Experience:</th>
                                <td>{{ $teacher->teaching_experience_years }} years</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ $teacher->address }}</td>
                            </tr>
                            <tr>
                                <th>Subjects Teaching:</th>
                                <td>
                                    @foreach ($teacher->subjects as $subject)
                                        <span class="badge bg-primary">{{ $subject->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>Classes:</th>
                                <td>
                                    @foreach ($teacher->classes as $class)
                                        <span class="badge bg-info">{{ $class->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Back to List</a>
                        @can('update', $teacher)
                            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning">Edit Profile</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsectionforeach($teacher->subjects as $subject)
    <span class="badge bg-primary">{{ $subject->name }}</span>
    @endforeach
    </td>
    <td>
        @foreach ($teacher->classes as $class)
            <span class="badge bg-info">{{ $class->name }}</span>
        @endforeach
    </td>
    <td>
        <div class="btn-group">
            <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-sm btn-info">
                <i class="fas fa-eye"></i>
            </a>
            @can('update', $teacher)
                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i>
                </a>
            @endcan
            @can('delete', $teacher)
                <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"
                        onclick="return confirm('Are you sure? This will unassign all subjects from this teacher.')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            @endcan
        </div>
    </td>
    </tr>
    @endforeach
    </tbody>
    </table>
    </div>
    {{ $teachers->links() }}
    </div>
    </div>
    </div>
@endsection
