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
                                <label for="type" class="form-label">Type Dropdown:</label>
                                <select class="select2 form-control select2-multiple" id="type" name="type"
                                    data-placeholder="Choosee..." multiple="multiple">
                                    <option value="">Select Options..</option>
                                    @foreach($labTestTypes as $type)
                                    <option value="{{$type->name}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
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
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true,
            tags: true,
            dropdownParent: $('#labResultsModal') // Ensure Select2 dropdown stays inside modal
        });

        $('#labResultForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            formData.append('types', $('#type').val())
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