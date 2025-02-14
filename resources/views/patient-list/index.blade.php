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
    })
</script>
@endsection