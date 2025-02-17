<div class="row">
    <div class="col-12">
        <div class="card"
            style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
            <div class="card-body">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Upcoming Appointment</h5>
                    </div>
                    <button class="btn btn-success"
                        id="addPatientAppointment">
                        ADD</button>
                </div>
                <div>
                    <table id="patientProfileTable" class="table table-bordered dt-responsive  patientProfileTable"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%; ">
                        <thead class="table-light">
                            <tr>
                                <th>Schedule</th>
                                <th>Doctor</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('appointment.appointment-form', compact('specializations'))
<script>
    $(document).ready(function() {
        $('#addPatientAppointment').on('click', function() {
            $('#patientAppointmentModal').modal('show');
        });

        $('.patientProfileTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-patient-appointment-profile-table') }}",
                type: "POST",
            },
            columns: [{
                    data: 'formatted_appointment_date',
                    name: 'formatted_appointment_date'
                },
                {
                    data: 'doctor_fullname',
                    name: 'doctor_fullname'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action'
                }

            ]
        });

        $('#patientAppointmentModal').on('hide.bs.modal', function() {
            $('#patientAppointmentForm').trigger('reset');
        });

        $(document).on('click', '.editAppointButton', function() {
            let id = $(this).attr('id'); // Assuming the button has a data-id attribute

            $.ajax({
                url: "/patient-appointment/" + id + "/edit",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "json",
                success: function(data) {
                    console.log(data); // Corrected from $data to data


                    // Populate form fields with the fetched data
                    $('#appointment_date').val(data.data.appointment_date);
                    $('select[name="specialization_id"]').val(data.data.doctor_profile.specialization_id);
                    $('#doctor_profile_id').val(data.data.doctor_profile_id);
                    $('select[name="status"]').val(data.data.status);

                    $('.appointmentButton').val('Update'); // Set the button text to Update
                    $('#patient_profile_id').val(id); // Set hidden input with the appointment ID

                    $('#patientAppointmentModal').modal('show');
                    fetchAvailableDoctors(data.data.appointment_date, data.data.doctor_profile.specialization_id, data.data.doctor_profile.id);
                },
                error: function(error) {
                    console.error('Error fetching appointment data:', error);
                },
            });
        });

    });
</script>