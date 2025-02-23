<div class="modal fade" id="patientAppointmentModal" tabindex="-1" aria-labelledby="patientAppointmentModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="patientAppointmentModalLabel">Add Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="patientAppointmentForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="appointment_date" class="form-label">Appointment Date:</label>
                                <input type="datetime-local" class="form-control" id="appointment_date" name="appointment_date" placeholder="Appointment Date">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label">Specialization:</label>
                                <select class="form-select" name="specialization_id">
                                    @foreach($specializations as $specialization)
                                    <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="patient_profile_id" id="patient_profile_id" value="{{$patientProfile->id}}">
                        <input type="hidden" name="doctor_profile_id" id="doctor_profile_id">
                    </div>

                    <div class="row mb-2">
                        <div class="col-6">
                            <label for="status" class="form-label">Status:</label>
                            <select name="status" class="form-select">
                                <option value="Scheduled">Scheduled</option>
                                <option value="Pending">Pending</option>
                                <option value="Done">Done</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-6" id="availableDoctorDiv" hidden>
                            <label for="appointment_date" class="form-label">Avialable Doctors:</label>
                            <select class="form-select" id="avialableDoctor">

                            </select>
                        </div>

                    </div>

                </div> <!-- Close modal-body here -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="appointment_action_button" id="appointment_action_button" value="Submit" class="btn btn-primary ladda-button appointmentButton" data-style="expand-right">
                </div>
            </form> <!-- Properly close the form here -->
        </div>
    </div>
</div>
<script>
    function fetchAvailableDoctors(date, specializationId, selectedDoctorId) {
        $.ajax({
            url: "{{ route('get-available-doctor') }}",
            method: 'POST',
            data: {
                availabilityDate: date,
                specializationId: specializationId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const doctors = response.data;
                let options = '<option value="">Select Doctor</option>';

                doctors.forEach(function(doctor) {
                    let selected = doctor.id == selectedDoctorId ? 'selected' : '';
                    options += `<option value="${doctor.id}" ${selected}>${doctor.firstname} ${doctor.lastname}</option>`;
                });

                $('#availableDoctorDiv').removeAttr('hidden');
                $('#avialableDoctor').html(options);
            },
            error: function(error) {
                console.error('Error fetching available doctors:', error);
            }
        });
    }

    $(document).ready(function() {
        $('#appointment_date, select[name="specialization_id"]').on('change', function() {
            let date = $('#appointment_date').val();
            let specialization = $('select[name="specialization_id"]').val();
            if (date && specialization) {
                fetchAvailableDoctors(date, specialization, null)

            }
        });

        $('#avialableDoctor').on('change', function() {
            $('#doctor_profile_id').val($(this).val());
        });

        $('#patientAppointmentForm').on('submit', function(e) {
            e.preventDefault();
            var hiddenId = $('#patient_profile_id').val();
            var url = $('#appointment_action_button').val() == 'Update' ? `{{ route('patient-appointment.update',':id') }}`.replace(':id', hiddenId) : "{{ route('patient-appointment.store') }}";
            var method = $('#appointment_action_button').val() == 'Update' ? 'PUT' : 'POST';
            var formData = new FormData(this);
            console.log('url', hiddenId);
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
                        text: method === 'PUT' ? 'Patient Appointment has been updated!' : 'Patient Appointment has been added!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $('#patientAppointmentModal').modal('hide');
                        $('.patientProfileTable').DataTable().ajax.reload();
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

        $('#patientAppointmentModal').on('hidden.bs.modal', function() {
            $('#patientAppointmentForm')[0].reset(); // Reset the form
            $('#doctor_profile_id').val(''); // Clear doctor selection
            $('#availableDoctorDiv').attr('hidden', true); // Hide doctor dropdown
            $('#appointment_action_button').val('Submit'); // Hide doctor dropdown
        });

    });
</script>