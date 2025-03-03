<div class="row">
    <div class="col-12">
        <div class="card"
            style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
            <div class="card-body">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Log Fluid Output</h5>
                    </div>
                    <button class="btn btn-success"
                        id="addFluidOutput">
                        ADD</button>
                </div>
                <div>
                    <table id="fluidOutputTable" class="table table-bordered dt-responsive fluidOutputTable"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%; ">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('patient-fluid-monitoring.patient-fluid-output-form', compact('patientProfile'))
<script>
    $(document).ready(function() {
        $('#addFluidOutput').on('click', function() {
            $('#fluidOutputModal').modal('show');
        });

        $('.fluidOutputTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-patient-fluid-output-table') }}",
                type: "POST",
                data: function(d) {
                    d.patient_profile_id = "{{$patientProfile->id}}"; // Assuming there's an input or hidden field with this ID
                }
            },
            columns: [{
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'time',
                    name: 'time'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ]
        });

        $(document).on('click', '.editFluidOutput', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            console.log(id);
            $.ajax({
                url: "/fluid-output/" + id + "/edit",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "json",
                success: function(data) {
                    console.log(data.data.time_date);
                    $('#time_date_output').val(data.data.time_date);
                    $('#amount_output').val(data.data.amount);
                    $('#hidden_id_output').val(data.data.id);
                    $('#fluid_output_action_button').val('Update');
                    $('#fluidOutputModal').modal('show');
                },
                error: function(data) {

                },
            });
        });

        $(document).on('click', '.deleteFluidOutput', function(e) {
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
                        url: `{{ route('fluid-output.destroy',':id') }}`.replace(':id', deleteId),
                        type: 'DELETE',

                        dataType: "json",
                        success: function(data) {
                            Swal.fire({
                                title: 'Success!',
                                text: "Deleted",
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                $('.fluidOutputTable').DataTable().ajax.reload();
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