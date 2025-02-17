<div class="row">
    <div class="col-12">
        <div class="card"
            style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
            <div class="card-body">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Lab Results</h5>
                    </div>
                    <button class="btn btn-success"
                        id="uploadLabResults">
                        UPLOAD</button>
                </div>
                <div>
                    <table id="labResultTable" class="table table-bordered dt-responsive  labResultTable"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%; ">
                        <thead class="table-light">
                            <tr>
                                <th>Type</th>
                                <th>File</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('lab-results.lab-result-form', compact('patientProfile'))
<script>
    $(document).ready(function() {
        $('#uploadLabResults').on('click', function() {
            $('#labResultsModal').modal('show');
        });

        $('.labResultTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-lab-results-table') }}",
                type: "POST",
            },
            columns: [{
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'file',
                    name: 'file'
                },
                {
                    data: 'date_uploaded',
                    name: 'date_uploaded'
                },
                {
                    data: 'action',
                    name: 'action'
                }

            ]
        });

        $(document).on('click', '.deleteFileButton', function() {
            var deleteFileId = $(this).attr('id');
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
                        url: `{{ route('lab-results.destroy',':id') }}`.replace(':id', deleteFileId),
                        type: 'DELETE',

                        dataType: "json",
                        success: function(data) {
                            Swal.fire({
                                title: 'Success!',
                                text: "Deleted",
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                // $('#dataModal').modal('hide');
                                $('.labResultTable').DataTable().ajax.reload();
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