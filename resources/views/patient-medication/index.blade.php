<div class="row">
    <div class="col-12">
        <div class="card"
            style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
            <div class="card-body">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Patient Medication</h5>
                    </div>
                    <button class="btn btn-success"
                        id="patientMedication">
                        ADD</button>
                </div>
                <div>
                    <table id="patientMedicationTable" class="table table-bordered dt-responsive  patientMedicationTable"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%; ">
                        <thead class="table-light">
                            <tr>
                                <th>Medication</th>
                                <th>Dosage</th>
                                <th>Schedule</th>
                                <th>Next Dose Time</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('patient-medication.patient-medication-form')

<script>
    $(document).ready(function() {
        $('.patientMedicationTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-patient-medication-table') }}",
                type: "POST",
                data: function(d) {
                    d.patient_profile_id = "{{$patientProfile->id}}"; // Assuming there's an input or hidden field with this ID
                }
            },
            columns: [{
                    data: 'medication_name',
                    name: 'medication_name'
                },
                {
                    data: 'dosage',
                    name: 'dosage'
                },
                {
                    data: 'interval_schedule',
                    name: 'interval_schedule'
                },
                {
                    data: 'next_dose_time',
                    name: 'next_dose_time'
                },
                {
                    data: 'action',
                    name: 'action'
                }

            ]
        });

        $('#patientMedication').on('click', function() {
            $('#patientMedicationFormModal').modal('show');
        });

        $(document).on('click', '.editButton', function() {
            let id = $(this).attr('id');

            $.ajax({
                url: `{{ route('patient-medication.edit', ':id') }}`.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#patientMedicationFormModalLabel').text('Edit Patient Medication');
                    $('#action_button').val('Update');
                    $('#hidden_id').val(data.data.id);

                    // Populate form fields
                    $('#medication_name').val(data.data.medication_name);
                    $('#dosage').val(data.data.dosage);
                    $('input[name="start_date"]').val(data.data.start_date);
                    $('#endDateInput').val(data.data.end_date);

                    // Handle "No End Date" checkbox
                    if (!data.data.end_date) {
                        $('#noEndDate').prop('checked', true);
                        $('#endDateInput').val('').prop('disabled', true);
                    } else {
                        $('#noEndDate').prop('checked', false);
                        $('#endDateInput').prop('disabled', false);
                    }

                    // Clear old schedules
                    $('#scheduleContainer').empty();

                    // Populate schedule
                    data.data.medication_schedule.forEach(schedule => {
                        let scheduleRow = `
                <div class="row mb-2 schedule-item align-items-center">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label class="form-label">Day of Week:</label>
                            <select class="form-select" name="day_of_week[]">
                                <option value="Everyday" ${schedule.day_of_week === 'Everyday' ? 'selected' : ''}>Everyday</option>
                                <option value="Monday" ${schedule.day_of_week === 'Monday' ? 'selected' : ''}>Monday</option>
                                <option value="Tuesday" ${schedule.day_of_week === 'Tuesday' ? 'selected' : ''}>Tuesday</option>
                                <option value="Wednesday" ${schedule.day_of_week === 'Wednesday' ? 'selected' : ''}>Wednesday</option>
                                <option value="Thursday" ${schedule.day_of_week === 'Thursday' ? 'selected' : ''}>Thursday</option>
                                <option value="Friday" ${schedule.day_of_week === 'Friday' ? 'selected' : ''}>Friday</option>
                                <option value="Saturday" ${schedule.day_of_week === 'Saturday' ? 'selected' : ''}>Saturday</option>
                                <option value="Sunday" ${schedule.day_of_week === 'Sunday' ? 'selected' : ''}>Sunday</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label class="form-label">Time:</label>
                            <div class="d-flex">
                                <input type="time" class="form-control me-2" name="start_time[]" value="${schedule.time}">
                                 <button type="button" class="btn btn-success add-schedule">Add</button>
                                <button type="button" class="btn btn-danger remove-schedule">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>`;

                        $('#scheduleContainer').append(scheduleRow);
                    });

                    // Show modal
                    $('#patientMedicationFormModal').modal('show');
                }
            });
        });

        // Handle removing a schedule
        $('#scheduleContainer').on('click', '.remove-schedule', function() {
            $(this).closest('.schedule-item').remove();
        });

        $(document).on('click', '.deleteButton', function(e) {
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
                        url: `{{ route('patient-medication.destroy',':id') }}`.replace(':id', deleteId),
                        type: 'DELETE',

                        dataType: "json",
                        success: function(data) {
                            Swal.fire({
                                title: 'Success!',
                                text: "Deleted",
                                icon: 'success',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                $('#labResultTypeModal').modal('hide');
                                $('.labResultTypeTable').DataTable().ajax.reload();
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