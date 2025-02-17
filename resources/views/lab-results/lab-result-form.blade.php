<div class="modal fade" id="labResultsModal" tabindex="-1" aria-labelledby="labResultsModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labResultsModalLabel">Lab Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="labResultForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="type" class="form-label">Type:</label>
                                <input type="text" class="form-control" id="type" name="type" placeholder="Type">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="lab_result_file" class="form-label">Upload File:</label>
                                <input type="file" class="form-control" id="lab_result_file" name="lab_result_file" placeholder="Upload File">
                            </div>
                        </div>
                        <input type="hidden" name="patient_profile_id" id="patient_profile_id" value="{{$patientProfile->id}}">
                    </div>

                    <div class="row mb-2">


                    </div>

                </div> <!-- Close modal-body here -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="lab_result_action_button" id="lab_result_action_button" value="Submit" class="btn btn-primary ladda-button appointmentButton" data-style="expand-right">
                </div>
            </form> <!-- Properly close the form here -->
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#labResultForm').on('submit', function(e) {
            e.preventDefault();
            // var hiddenId = $('#patient_profile_id').val();
            // var url = $('#lab_result_action_button').val() == 'Update' ? `{{ route('lab-results.update',':id') }}`.replace(':id', hiddenId) : "{{ route('lab-results.store') }}";
            // var method = $('#lab_result_action_button').val() == 'Update' ? 'PUT' : 'POST';
            var formData = new FormData(this);
            // console.log('url', hiddenId);
            // if (method === 'PUT') {
            //     formData.append('_method', 'PUT');
            // }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('lab-results.store') }}",
                type: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting content-type
                dataType: "json",
                success: function(data) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Lab results has been uploaded!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $('#labResultsModal').modal('hide');
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
        });
    });
</script>