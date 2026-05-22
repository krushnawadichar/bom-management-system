@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">ID</th>
                            <td>{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <th>Employee ID</th>
                            <td>{{ $user->employee_id ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>{{ $user->department ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge badge-info">{{ $role->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $user->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit User
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection