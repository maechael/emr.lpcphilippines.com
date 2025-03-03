<div class="modal fade" id="patientMedicationFormModal" tabindex="-1" aria-labelledby="patientMedicationFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="patientMedicationFormModalLabel">Add Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="patientMedicationForm" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="medication_name" class="form-label">Medication Name:</label>
                                <input type="text" class="form-control" id="medication_name" name="medication_name" placeholder="Medication Name">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="dosage" class="form-label">Dosage:</label>
                                <input type="text" class="form-control" id="dosage" name="dosage" placeholder="Dosage">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label class="form-label">Start Date:</label>
                            <input type="date" class="form-control" name="start_date">
                        </div>
                        <div class="col-6">
                            <label class="form-label">End Date:</label>
                            <input type="date" class="form-control" id="endDateInput" name="end_date">
                            <div class="form-check mt-1">
                                <input type="checkbox" class="form-check-input" id="noEndDate">
                                <label class="form-check-label" for="noEndDate">No End Date</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div>
                            <label for="schedule" class="form-label">Schedule:</label>
                        </div>
                    </div>
                    <div id="scheduleContainer">
                        <div class="schedule-item row mb-2 align-items-end">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="form-label">Day of Week:</label>
                                    <select class="form-select" name="day_of_week[]">
                                        <option value="Everyday">Everyday</option>
                                        @foreach($dayOfWeekOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="form-label">Time:</label>
                                    <div class="d-flex">
                                        <input type="time" class="form-control me-2" name="start_time[]">
                                        <button type="button" class="btn btn-success add-schedule">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="patient_profile_id" id="patient_profile_id" value="{{$patientProfile->id}}">
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
        // Disable end date input when "No End Date" is checked
        $('#noEndDate').change(function() {
            if ($(this).is(':checked')) {
                $('#endDateInput').val('').prop('disabled', true);
            } else {
                $('#endDateInput').prop('disabled', false);
            }
        });

        $('#scheduleContainer').on('click', '.add-schedule', function() {
            let newRow = $(this).closest('.schedule-item').clone();
            newRow.find('input').val('');

            if ($('#action_button').val() !== 'Update') { // Fixing the condition check
                newRow.find('.add-schedule')
                    .removeClass('btn-success add-schedule')
                    .addClass('btn-danger remove-schedule')
                    .text('Remove');
            }

            $('#scheduleContainer').append(newRow);
        });

        $('#scheduleContainer').on('click', '.remove-schedule', function() {
            $(this).closest('.schedule-item').remove();
        });


        $('#patientMedicationForm').on('submit', function(e) {
            e.preventDefault();
            var hiddenId = $('#hidden_id').val();
            var url = $('#action_button').val() == 'Update' ? `{{ route('patient-medication.update',':id') }}`.replace(':id', hiddenId) : "{{ route('patient-medication.store') }}";
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
                        text: method === 'PUT' ? 'Patient Medication has been updated!' : 'Patient Medication has been added!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $('#patientMedicationFormModal').modal('hide');
                        $('.patientMedicationTable').DataTable().ajax.reload();
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

        $('#patientMedicationFormModal').on('hide.bs.modal', function(e) {
            $('#patientMedicationForm').trigger('reset');
            // Remove all dynamically added schedule items except the first template row
            $('#scheduleContainer .schedule-item:not(:first)').remove();
        });

    })
</script>