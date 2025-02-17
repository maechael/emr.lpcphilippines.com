@extends('layouts.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Patient List</h5>
                    </div>
                    <button class="btn btn-success"
                        id="addPatientButton">
                        ADD PATIENT</button>
                </div>
                <div>
                    <table id="patientListTable" class="table table-bordered dt-responsive  patientListTable"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%; ">
                        <thead class="table-light">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Age</th>
                                <th>Contact Number</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead><!-- end thead -->

                    </table> <!-- end table -->
                </div>
            </div>
        </div>

        @include('patient-list.patient-list-form')
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.patientListTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-patient-list') }}",
                type: "POST",
            },
            columns: [{
                    data: 'firstname',
                    name: 'firstname'
                },
                {
                    data: 'lastname',
                    name: 'lastname'
                },
                {
                    data: 'age',
                    name: 'age'
                },
                {
                    data: 'contact_number',
                    name: 'contact_number'
                },
                {
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'action',
                    name: 'action'
                }

            ]
        });

        $('#addPatientButton').on('click', function(e) {
            console.log('clicked');
            $('#dataModal').modal('show');
        });

        $('#dataModal').on('hide.bs.modal', function() {
            $('#patientListForm').trigger('reset');
            $('#action_button').val('Submit');
        });

        $(document).on('click', '.editButton', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            console.log(id);
            $.ajax({
                url: "/patient-list/" + id + "/edit",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#firstname').val(data.data.firstname);
                    $('#lastname').val(data.data.lastname);
                    $('#birthdate').val(data.data.birthdate);
                    $('#contact_number').val(data.data.contact_number);
                    if (data.data.is_pwd == 1) {
                        $('#pwdNumberDiv').removeAttr('hidden'); // Show PWD number field
                        $('#is_pwd').prop('checked', true); // Correctly set the checkbox
                        $('#pwd_number').val(data.data.pwd_number);
                    } else {
                        $('#pwdNumberDiv').attr('hidden', true); // Hide PWD number field
                        $('#is_pwd').prop('checked', false); // Uncheck if not PWD
                    }
                    $('#address').val(data.data.address);
                    $('#hidden_id').val(data.data.id);
                    $('#action_button').val('Update');
                    $('#dataModal').modal('show');
                },
                error: function(data) {

                },
            });
        });

        $(document).on('click', '.deleteButton', function(e) {
            var deleteId = $(this).attr('id');
            Swal.fire({
                title: 'Confirm File Removal',
                text: 'Are you sure you want to remove this file?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: `{{ route('patient-list.destroy',':id') }}`.replace(':id', deleteId),
                        type: 'DELETE',

                        dataType: "json",
                        success: function(data) {
                            Swal.fire({
                                title: 'Success!',
                                text: "Deleted",
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                $('#dataModal').modal('hide');
                                $('.patientListTable').DataTable().ajax.reload();
                            });
                        },
                        error: function(data) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong!',
                                icon: 'error',
                                confirmButtonText: 'Ok'
                            });
                        }
                    });
                }

            });
        });

        $(document).on('click', '.viewButton', function(e) {
            var showId = $(this).attr('id');
            window.location.href = `{{ route('patient-list.show',':id') }}`.replace(':id', showId);

        });

    })
</script>
@endsection