<div class="modal fade" id="fluidOutputModal" tabindex="-1" aria-labelledby="fluidOutputModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fluidOutputModalLabel">Fluid Output</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="fluidOutputForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="time_date" class="form-label">Time and Date:</label>
                                <input id="time_date_output" type="datetime-local" name="time_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="amount" class="form-label">Amount:</label>
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend">
                                        <span class="input-group-text">ml</span>
                                    </span><input id="amount_output" type="text" name="amount" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="hidden_id_output" id="hidden_id_output">
                <input type="hidden" name="patient_profile_id" id="patient_profile_id" value="{{$patientProfile->id}}">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="fluid_output_action_button" id="fluid_output_action_button" value="Submit"
                        class="btn btn-primary ladda-button " data-style="expand-right">
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#fluidOutputForm').on('submit', function(e) {
            e.preventDefault();
            var hiddenId = $('#hidden_id_output').val();
            var url = $('#fluid_output_action_button').val() == 'Update' ? `{{ route('fluid-output.update',':id') }}`.replace(':id', hiddenId) : "{{ route('fluid-output.store') }}";
            var method = $('#fluid_output_action_button').val() == 'Update' ? 'PUT' : 'POST';
            var formData = new FormData(this);

            console.log(url);
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting content-type
                dataType: "json",
                success: function(data) {
                    Swal.fire({
                        title: 'Success!',
                        text: method === 'PUT' ? 'fluidOutput has been updated!' : 'fluidOutput has been added!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $('#fluidOutputModal').modal('hide');
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
        });
    });
</script>