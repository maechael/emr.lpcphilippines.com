<div class="row">
    <div class="col-12">
        <div class="card"
            style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
            <div class="card-body">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Lab Imaging</h5>
                    </div>
                    @can('upload_lab_imaging', App\Models\PatientProfile::find(1))
                    <button class="btn btn-success"
                        id="uploadLabImaging">
                        UPLOAD</button>
                    @endcan
                </div>
                <div>
                    <table id="labImaging" class="table table-bordered dt-responsive  labImaging"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%; ">
                        <thead class="table-light">
                            <tr>
                                <th>Type</th>
                                <th>File</th>
                                <th>Description</th>
                                <th>Date Tested</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('lab-results.lab-imaging-form', compact('patientProfile'))
<script>
    $(document).ready(function() {
        $('#uploadLabImaging').on('click', function() {
            $('#labImagingModal').modal('show');
        });

        $('.labImaging').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-lab-imaging-table') }}",
                type: "POST",
                data: function(d) {
                    d.patient_profile_id = "{{$patientProfile->id}}"; // Assuming there's an input or hidden field with this ID
                }
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
                    data: 'description',
                    name: 'description',
                    render: function(data, type, row) {
                        return data ? $('<div>').html(data).text() : ''; // Decodes HTML entities
                    }
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

        $(document).on('click', '.deleteLabImaging', function() {
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
                        url: `{{ route('lab-imaging.destroy',':id') }}`.replace(':id', deleteFileId),
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
                                $('.labImaging').DataTable().ajax.reload();
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