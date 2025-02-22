@extends('layouts.admin_master')
@section('admin')


<div class="page-content">
    <div class="container-fluid">
        <style>
            .hidden-permission {
                display: none;
            }

            .permissions-container {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }

            .permission-badge {
                flex: 0 0 20%;
                margin-bottom: 5px;
                box-sizing: border-box;
                padding: 0 5px;
            }

            .see-less-button {
                display: none;
            }
        </style>

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0"></h4>

                    {{-- <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">Upcube</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div> --}}

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">


            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dropdown float-end">

                                <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                    ADD ROLE </a>

                            </div>

                            <h4 class="card-title mb-4">Roles</h4>

                            <div>
                                <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Permission</th>
                                            <th>Created at</th>
                                            <th>Updated at</th>
                                            <th style="width: 120px;">Action</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>
                                        @foreach ($roles as $role)
                                        <tr>
                                            <td>
                                                <h6 class="mb-0">{{ $role->id }}</h6>
                                            </td>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                <div class="permissions-container">
                                                    @foreach($role->permissions as $index => $rp)
                                                    <span class="badge bg-info permission-badge {{ $index >= 3 ? 'hidden-permission' : '' }}">
                                                        <h6>{{ $rp->name }}</h6>
                                                    </span>
                                                    @endforeach
                                                </div>
                                                @if(count($role->permissions) > 3)
                                                <button class="btn btn-sm btn-link see-more-button">...</button>
                                                <button class="btn btn-sm btn-link see-less-button">...</button>
                                                @endif
                                            </td>
                                            <td>{{ $role->created_at }}</td>
                                            <td>{{ $role->updated_at }}</td>
                                            <td>
                                                <div class="row">

                                                    <div class="col-5">

                                                        <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-info"><i class="ri-edit-box-line"></i></a>

                                                    </div>

                                                    <div class="col-4 ml-0">

                                                        <form
                                                            action="{{ route('admin.roles.destroy', $role->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-danger" onclick="return confirmDelete()" type="submit"><i class="ri-delete-bin-line"></i></button>
                                                        </form>

                                                    </div>
                                                </div>


                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody><!-- end tbody -->
                                </table> <!-- end table -->
                            </div>
                        </div><!-- end card -->
                    </div><!-- end card -->
                </div>
                <!-- end col -->



            </div>
            <!-- end row -->
        </div>

    </div>

    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleLabel">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="submitBtn" class="btn btn-success">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#submitBtn').click(function(e) {
                e.preventDefault(); // prevent form from submitting
                var name = $('#name').val();
                $.ajax({
                    url: '{{ route("admin.roles.store") }}', // updated URL
                    type: 'POST',
                    data: {
                        name: name,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // handle success
                        $('#addRoleModal').modal('hide'); // close modal
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // handle error
                        console.log(xhr.responseText);
                    }
                });
            });
        });

        $(document).ready(function() {
            $('.see-more-button').on('click', function(e) {
                e.preventDefault();
                const permissionsContainer = $(this).prev();
                const hiddenPermissions = permissionsContainer.find('.hidden-permission');

                hiddenPermissions.css('display', 'inline-block');
                $(this).css('display', 'none');
                $(this).next().css('display', 'inline');
            });

            $('.see-less-button').on('click', function(e) {
                e.preventDefault();
                const permissionsContainer = $(this).prev().prev();
                const hiddenPermissions = permissionsContainer.find('.hidden-permission');

                hiddenPermissions.css('display', 'none');
                $(this).css('display', 'none');
                $(this).prev().css('display', 'inline');
            });
        });

        function confirmDelete() {
            return confirm("Are you sure you want to delete this permission?");
        }
    </script>

    @endsection