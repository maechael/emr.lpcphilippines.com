<div class="modal fade" id="labImagingModal" tabindex="-1" aria-labelledby="labImagingModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="labImagingModalLabel">Lab Imaging</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="labImagingForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="type" class="form-label">Image Type:</label>
                                <input type="text" class="form-control" id="type" name="type" placeholder="type">
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

                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="date_tested" class="form-label">Date Tested:</label>
                                <input type="datetime-local" class="form-control" id="date_tested" name="date_tested" placeholder="Date Tested">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="description" class="form-label">Description:</label>
                                <textarea class="form-control" rows="5" id="description" name="description"></textarea>
                            </div>
                        </div>
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
        $('#labImagingForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('types', $('#type').val())
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('lab-imaging.store') }}",
                type: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting content-type
                dataType: "json",
                success: function(data) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Lab Imaging has been uploaded!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $('#labImagingModal').modal('hide');
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
        });
    });
</script>