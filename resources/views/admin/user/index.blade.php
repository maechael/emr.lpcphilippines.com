@extends('layouts.admin_master')
@section('admin')
<style>
    ul.error-list {
        list-style: none !important;
        margin: 0;
        padding: 0;
    }
</style>

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
        <div class="row">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="dropdown float-end">
                                <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#dataModal"
                                    id="create_record">
                                    ADD USER</a>
                            </div>
                            <h4 class="card-title mb-4">Users</h4>
                            <div>
                                <table id="usersTable" class="table table-bordered dt-responsive nowrap usersTable"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Email</th>
                                            <th>Created at</th>
                                            <th>Updated at</th>
                                            <th style="width: 120px;">Action</th>
                                        </tr>
                                    </thead><!-- end thead -->
                                    <tbody>

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
        {{-- @include('admin.user.edit', ['user' => $user, 'roles' => $roles]) --}}
    </div>


    {{-- Start of modal for creation of user --}}
    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="dataModalForm" method="POST">
                    <div class="modal-body">

                        @csrf
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                            <div class="invalid-feedback" id="nameError"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Email" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" class="form-control mb-3" id="password" name="password"
                                placeholder="Password" required>

                            <label for="password_confirmation">Password Confirmation</label>
                            <input type="password" class="form-control" placeholder="Password Confirmation"
                                name="password_confirmation" id="password_confirmation" required>
                            <div class="invalid-feedback" id="passwordError"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="role_id" class="form-label">Roles</label>
                            <select name="role_id" id="role_id" class="form-control" required>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="action" id="action" value="add">
                        <input type="hidden" name="hidden_id" id="hidden_id" />

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" name="action_button" id="action_button" value="Add"
                            class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End of modal --}}

</div>


{{-- start of deletion of modal --}}
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Confirmation</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to remove this data?.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" name="ok_button" id="ok_button">Submit</button>
            </div>
        </div>
    </div>
</div>
{{-- end of deletion of modal --}}

<script>
    // DATA TABLE
    $(document).ready(function() {
        $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.users.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'role_name',
                    name: 'role_name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

    });



    // configuring of modal for creating
    $('#create_record').on('click', function(event) {
        $('.modal-title').text('Add New Record');
        // $('#name').val(data.result.name);
        $('#action_button').val('Add');
        $('#action').val('Add');
        $('#dataModal').modal('show');
    })

    // script for configuring edit modal
    $(document).on('click', '.edit', function(event) {
        event.preventDefault();
        var id = $(this).attr('id');
        $('#form_result').html
        $.ajax({
            url: "/admin/users/" + id + "/edit",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            success: function(data) {
                $('#name').val(data.result.name);
                $('#username').val(data.result.username);
                $('#email').val(data.result.email);
                $('#role_id').val(data.result.role_id);
                $('#hidden_id').val(id);
                $('.modal-title').text('Edit Record');
                $('#action_button').val('Update');
                $('#action').val('Update');
                $('#dataModal').modal('show');
                $('#password, #password_confirmation').css('display', 'none');
                $('#errorPassword').css('display', 'none');
                var passwordLabel = $('label[for="password"]');
                var passwordConfirmationLabel = $('label[for="password_confirmation"]');
                $('#password, #password_confirmation').removeAttr('required');
                passwordLabel.hide();
                passwordConfirmationLabel.hide();
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
            }

        })

    })

    //script for submission of form
    $('#dataModalForm').on('submit', function(event) {
        event.preventDefault();
        var action_url = '';

        if ($('#action').val() == 'Add') {
            action_url = "{{ route('admin.users.store') }}"
        } else if ($('#action').val() == 'Update') {
            action_url = "{{ route('admin.users.update') }}"
        }

        var name = $('#name').val();

        //form sending on creationg
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: action_url,
            data: $(this).serialize(),
            success: function(response) {
                $('#dataModal').modal('hide');
                location.reload();
            },
            error: function(jqXHR, xhr, status, error) {
                if (jqXHR.status == 422) {
                    const errors = jqXHR.responseJSON.errors;
                    // Remove any existing errors
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').empty();

                    // Loop through and display error messages
                    for (let field in errors) {
                        const errorMessage = errors[field];
                        console.log(field);
                        $(`#${field}`).addClass('is-invalid');
                        $(`#${field}Error`).text(errorMessage[0]).show();
                    }
                }

            },
        });
    })



    //script for deletion
    var UserId
    $(document).on('click', '.delete', function() {
        console.log('test')
        UserId = $(this).attr('id');
        console.log(UserId);
        $('#confirmModal').modal('show');
    })



    //script for sending delete
    $('#ok_button').click(function() {
        $.ajax({
            url: "/admin/users/" + UserId,
            type: "DELETE",
            beforeSend: function() {
                $('#ok_button').text('Deleting.....');
            },
            success: function($data) {
                setTimeout(() => {
                    $('#confirmModal').modal('hide');
                    location.reload();
                }, 1000);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.status);
                console.log('Error: ' + textStatus + ' - ' + errorThrown);
            }
        });
    });

    $(document).ready(function() {
        $("#dataModal").on('hidden.bs.modal', function() {
            var form = document.querySelector('#dataModalForm');
            form.reset();
            $('#password, #password_confirmation').css('display', 'block');
            $('#errorPassword').html('').removeClass('error-text');
            var passwordLabel = $('label[for="password"]');
            var passwordConfirmationLabel = $('label[for="password_confirmation"]');
            $('#password, #password_confirmation').attr('required', 'required');
            passwordLabel.show();
            passwordConfirmationLabel.show();
            $('#action_button').val('Add');
            $('#action').val('add');
            $('.modal-title').text('Add New Record');
        });
    });

    $(document).ready(function() {
        $('#dataModalForm').parsley();
    });
</script>


@endsection