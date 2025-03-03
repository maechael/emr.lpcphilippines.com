@extends('layouts.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Lab Result Type</h5>
                    </div>
                    <button class="btn btn-success"
                        id="addLabResultType">
                        ADD</button>
                </div>
                <div>
                    <table id="labResultTypeTable" class="table table-bordered dt-responsive labResultTypeTable"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%; ">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Created_at</th>
                                <th>Updated_at</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('lab-result-type.lab-result-form');
<script>
    $(document).ready(function(e) {
        $('.labResultTypeTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-lab-result-type-table') }}",
                type: "POST",
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'formatted_created_at',
                    name: 'formatted_created_at'
                },
                {
                    data: 'formatted_updated_at',
                    name: 'formatted_updated_at'
                },
                {
                    data: 'action',
                    name: 'action'
                }

            ]
        });

        $('#addLabResultType').on('click', function() {
            $('#labResultTypeModal').modal('show');
        })

        $('#labResultTypeModal').on('hidden.bs.modal', function() {
            $('#labResultTypeForm').trigger('reset');
            $('#action_button').val('Submit');
        });

        $(document).on('click', '.editButton', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            console.log(id);
            $.ajax({
                url: "/lab-result-type/" + id + "/edit",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#name').val(data.data.name);
                    $('#hidden_id').val(data.data.id);
                    $('#action_button').val('Update');
                    $('#labResultTypeModal').modal('show');
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
                        url: `{{ route('lab-result-type.destroy',':id') }}`.replace(':id', deleteId),
                        type: 'DELETE',

                        dataType: "json",
                        success: function(data) {
                            Swal.fire({
                                title: 'Success!',
                                text: "Deleted",
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                $('#labResultTypeModal').modal('hide');
                                $('.labResultTypeTable').DataTable().ajax.reload();
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

    });
</script>

@endsection