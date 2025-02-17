@extends('layouts.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Specialization List</h5>
                    </div>
                    <button class="btn btn-success"
                        id="addSpecialization">
                        ADD</button>
                </div>
                <div>
                    <table id="specializationTable" class="table table-bordered dt-responsive  specializationTable"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%; ">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead><!-- end thead -->

                    </table> <!-- end table -->
                </div>
            </div>
        </div>
    </div>
</div>
@include('specialization.specialization-form')
<script>
    $(document).ready(function(e) {
        $('.specializationTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-specialization-table') }}",
                type: "POST",
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'action',
                    name: 'action'
                }

            ]
        });

        $('#addSpecialization').on('click', function() {
            $('#specializationModal').modal('show');
        })

        $(document).on('click', '.editButton', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            console.log(id);
            $.ajax({
                url: "/specialization/" + id + "/edit",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#name').val(data.data.name);
                    $('#hidden_id').val(data.data.id);
                    $('#action_button').val('Update');
                    $('#specializationModal').modal('show');
                },
                error: function(data) {

                },
            });
        });
    })
</script>
@endsection