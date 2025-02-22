@extends('layouts.admin_master')
@section('admin')


<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0"></h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <h1 class="my-4">Assign Permission to Role</h1>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role_id">
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        </select>
                    </div>
                    <form action="{{ route ('admin.roles.permissions', $role->id)}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="permissions" class="form-label">Permissions</label>
                            <div class="row">
                                @foreach($permissions as $permission)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="permission-{{ $permission->id }}" value="{{ $permission->id }}" {{ $role->hasPermission($permission->name) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="permission-{{ $permission->id }}">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Assign Permissions</button>
                    </form>


                </div>
            </div>
        </div>


    </div>

</div>

</div>




@endsection