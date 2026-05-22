@extends('layouts.app')

@section('title', 'Create New User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New User</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" placeholder="Enter Name" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                            </div>
                            <div class="col-md-6">  
                        
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                            </div>
                            
                        
                            <div class="col-md-6">
                        <div class="form-group">
                            <label for="employee_id">Employee ID</label>
                            <input type="text" class="form-control @error('employee_id') is-invalid @enderror" 
                                   id="employee_id" name="employee_id" placeholder="Enter Employee ID" value="{{ old('employee_id') }}">
                            @error('employee_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                            </div>
                            <div class="col-md-6">
                        
                        <div class="form-group">
                            <label for="department">Department</label>
                            <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                   id="department" name="department"  placeholder="Enter Department" value="{{ old('department') }}">
                            @error('department')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                            </div>
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">Role *</label>
                            <select class="form-control @error('role') is-invalid @enderror" 
                                    id="role" name="role" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        </div>

                        <div class="col-md-6">
                        
                        <div class="form-group">
                            <label for="password">Password *</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Enter Password" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        </div>
                        
                        <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password *</label>
                            <input type="password" class="form-control" placeholder="Confirm Password"
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                        </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Create User</button>
                        <a href="{{ route('users.index') }}" class="btn btn-default">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection