<div class="modal fade" id="vitalSignModal" tabindex="-1" aria-labelledby="vitalSignModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vitalSignModalLabel">Log Vital Sign</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="vitalSignForm" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="blood_pressure" class="form-label">Blood Pressure</label>
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend">
                                        <span class="input-group-text">mmHg</span>
                                    </span><input id="blood_pressure" type="text" name="blood_pressure" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="temperature" class="form-label">Temperature:</label>
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend">
                                        <span class="input-group-text">°C</span>
                                    </span><input id="temperature" type="text" name="temperature" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="heart_rate" class="form-label">Heart Rate:</label>
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend">
                                        <span class="input-group-text">BPM</span>
                                    </span><input id="heart_rate" type="text" name="heart_rate" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="pulse_rate" class="form-label">Pulse Rate:</label>
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend">
                                        <span class="input-group-text">Hz</span>
                                    </span><input id="pulse_rate" type="text" name="pulse_rate" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label for="weight" class="form-label">Weight:</label>
                            <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend">
                                    <span class="input-group-text">Kg</span>
                                </span><input id="weight" type="text" name="weight" class="form-control">
                            </div>
                        </div>
                        <div class="col-6">
                        </div>
                    </div>

                    <input type="hidden" name="patient_profile_id" id="patient_profile_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="action_button" id="action_button" value="Submit"
                        class="btn btn-primary ladda-button patientlistButton" data-style="expand-right">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#vitalSignForm').on('submit', function(e) {
            e.preventDefault();
            var hiddenId = $('#hidden_id').val();
            var url = $('#action_button').val() == 'Update' ? `{{ route('vital-sign.update',':id') }}`.replace(':id', hiddenId) : "{{ route('vital-sign.store') }}";
            var method = $('#action_button').val() == 'Update' ? 'PUT' : 'POST';
            var formData = new FormData(this);

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
                        text: method === 'PUT' ? 'Vital Sign has been updated!' : 'Vital Sign has been added!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $('#vitalSignModal').modal('hide');
                        getLatestVitalSign(data.data.patient_profile_id);
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