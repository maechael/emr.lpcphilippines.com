<div class="row">
    <div class="col-12">
        <div class="card"
            style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
            <div class="card-body">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Other Document</h5>
                    </div>
                    @can('upload_lab_imaging', App\Models\PatientProfile::find(1))
                    <button class="btn btn-success"
                        id="uploadOtherDocument">
                        UPLOAD</button>
                    @endcan
                </div>
                <div>
                    <table id="otherDocument" class="table table-bordered dt-responsive  otherDocument"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%; ">
                        <thead class="table-light">
                            <tr>
                                <th>File</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('other-document.other-document-form', compact('patientProfile'))
<script>
    $(document).ready(function() {
        $('#uploadOtherDocument').on('click', function() {
            $('#otherDocumentModal').modal('show');
        });

        $('.otherDocument').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-other-document-table') }}",
                type: "POST",
                data: function(d) {
                    d.patient_profile_id = "{{$patientProfile->id}}"; // Assuming there's an input or hidden field with this ID
                }
            },
            columns: [{
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
                    data: 'action',
                    name: 'action'
                }

            ]
        });

        $(document).on('click', '.deleteotherDocument', function() {
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
                        url: `{{ route('other-document.destroy',':id') }}`.replace(':id', deleteFileId),
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
                                $('.otherDocument').DataTable().ajax.reload();
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