@extends('layouts.admin_master')
@section('admin')
<style>
    .custom-file-input-container {
        position: relative;
        width: 100%;
    }

    #media {
        opacity: 5;
        /* Make the actual input transparent */
    }

    .img-inside-input {
        position: absolute;
        top: 50%;
        left: 30%;
        transform: translate(-70%, -50%);
        max-width: 50px;
        max-height: 40px;
        display: none;
        /* Hide initially */
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
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dropdown float-end">
                            <a href="{{ route('admin.user-profiles.create') }}" class="btn btn-success"
                                data-bs-toggle="modal" data-bs-target="#dataModal" id="create_record">
                                ADD USERPROFILE</a>
                            {{-- <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical"></i>
                        </a> --}}
                        </div>
                        <h4 class="card-title mb-4">USER PROFILES</h4>
                        <div>
                            <table id="user-profiles-table" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th>ID Number</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Account</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

{{-- Start of modal for creation and edition --}}
<div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel">Add Position</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="dataModalForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <div class="form-group mb-2">
                            <label for="first_name" class="form-label">First Name:</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>

                        <div class="form-group mb-2">
                            <label for="last_name" class="form-label">Last Name:</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>



                        <div class="form-group mb-2">
                            <label for="id_num" class="form-label">ID Number:</label>
                            <input type="text" class="form-control" id="id_num" name="id_num" required>
                        </div>



                        <div class="form-group mb-2">
                            <label for="position" class="form-label">Designation:</label>
                            <input type="text" class="form-control" id="position" name="position" required>
                        </div>

                        <div class="form-group mb-2">
                            <label for="is_active" class="form-label">Status:</label>
                            <select name="is_active" id="is_active" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>



                        <div class="form-group mb-2">
                            <label for="account_id" class="form-label">Account:</label>
                            <select name="account_id" id="account_id" class="form-control">
                                @foreach($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="media">User's Image:</label>
                            <input type="file" class="form-control" id="media" name="media" required>
                            <img id="currentImage" src="" alt="Current Image" style="max-width: 100px; max-height: 100px;">
                        </div>

                    </div>

                    <input type="hidden" name="action" id="action" value="add">
                    <input type="hidden" name="hidden_id" id="hidden_id" />

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <input type="submit" name="action_button" id="action_button" value="Add" class="btn btn-primary">
            </div>

            </form>
        </div>
    </div>
</div>
</div>
{{-- End of modal --}}


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
                <button type="button" class="btn btn-danger" name="ok_button" id="ok_button">gege</button>
            </div>
        </div>
    </div>
</div>
{{-- end of deletion of modal --}}



<script>
    //DATA TABLE
    $(function() {
        $('#user-profiles-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.user-profiles.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'full_name',
                    name: 'full_name'
                },
                {
                    data: 'id_num',
                    name: 'id_num'
                },
                {
                    data: 'position_name',
                    name: 'position_name'
                },
                {
                    data: 'is_active',
                    name: 'is_active',
                    render: function(data, type, full, meta) {
                        var statusClass = data == 1 ? 'active' : 'inactive';
                        return '<span class="' + statusClass + '">' + (data == 1 ? 'Active' :
                            'Inactive') + '</span>';
                    }
                },

                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'created_at_formatted',
                    name: 'created_at'
                },
                {
                    data: 'updated_at_formatted',
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
        $('#datanModal').modal('show');
    })

    // script of sending modal form
    $('#dataModalForm').on('submit', function(event) {
        event.preventDefault();
        var form = $(this).parsley();
        var formElement = this;
        var button = $('#action_button');
        // var laddaButton = Ladda.create(button[0]);
        form.on('form:validate', function() {
            if (form.isValid()) {
                var formData = new FormData(formElement);
                formData.append('media', $('#media')[0].files[0]);
                // laddaButton.start();
                var action_url = '';
                if ($('#action').val() == 'Add') {
                    action_url = "{{ route('admin.user-profiles.store') }}"
                } else if ($('#action').val() == 'Update') {
                    action_url = "{{ route('admin.user-profiles.update') }}"
                }
                $.ajax({
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: action_url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // laddaButton.stop();
                        if (response.error) {
                            var errorMessage = response.error;
                            Swal.fire({
                                title: 'Error',
                                text: errorMessage,
                                icon: 'error'
                            }).then(function() {
                                $('#dataModal').modal('hide');
                                location.reload();
                            });
                        } else {
                            // handle success message
                            Swal.fire({
                                title: 'Success',
                                text: response.success,
                                icon: 'success'
                            }).then(function() {
                                $('#dataModal').modal('hide');
                                location.reload();
                            });
                        }
                    },
                    error: function(jqXHR, xhr, status, error) {
                        // laddaButton.stop();
                        if (jqXHR.status == 422) {
                            const errors = jqXHR.responseJSON.errors;

                            // Remove any existing errors
                            $('.is-invalid').removeClass('is-invalid');
                            $('.invalid-feedback').empty();

                            // Loop through and display error messages
                            for (let field in errors) {
                                const errorMessage = errors[field];

                                $(`#${field}`).addClass('is-invalid');
                                $(`#${field}Error`).text(errorMessage[0]).show();
                            }
                        }
                    },
                });
            }
        })
    })
    //script for deletion
    var user_profile_id
    $(document).on('click', '.delete', function() {
        user_profile_id = $(this).attr('id');
        $('#confirmModal').modal('show');
    })
    //script for sending delete
    $('#ok_button').click(function() {
        $.ajax({
            url: "/admin/user-profiles/" + user_profile_id,
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
        $('#dataModalForm').parsley();
        $('#dataModal').on('hidden.bs.modal', function(e) {
            $('#dataModalForm')[0].reset();
            $('#currentImage').hide();
        });
    });

    $('#account_id').prepend($('<option>', {
        value: '',
        text: 'Select account',
        selected: true,
        disabled: true
    }));

    $('#is_active').prepend($('<option>', {
        value: '',
        text: 'Select status',
        selected: true,
        disabled: true
    }));

    $(document).on('change', '#media', function() {
        var file = $(this)[0].files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#currentImage').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(file);
    })

    // script for configuring edit modal
    $(document).on('click', '.edit', function(event) {
        event.preventDefault();
        var id = $(this).attr('id');

        $.ajax({
            url: "/admin/user-profiles/" + id + "/edit",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            success: function(data) {
                var url = `{{ asset('${data.media.filepath}') }}`;
                //   console.log(data.accountsSelected);
                $('#first_name').val(data.result.firstname);
                $('#last_name').val(data.result.lastname);
                $('#id_num').val(data.result.id_num);
                $('#position').val(data.result.position);
                $('#is_active').val(data.result.is_active);
                $('#department_id').val(data.result.department_id);
                $('#account_id').val(data.result.user_id);
                $('#currentImage').attr('src', url).show();
                $('#media').attr('required', false);
                $('#hidden_id').val(id);
                $('.modal-title').text('Edit Record');
                $('#action_button').val('Update');
                $('#action').val('Update');
                $('#dataModal').modal('show');
            },
            error: function(data) {
                var errors = data.responseJSON;
                console.log(errors);
            }
        })



    })
</script>
@endsection