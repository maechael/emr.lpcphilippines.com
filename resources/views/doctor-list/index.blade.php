@extends('layouts.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Doctor List</h5>
                    </div>
                    <button class="btn btn-success"
                        id="addDoctorButton">
                        ADD</button>
                </div>
                <div>
                    <table id="doctorListTable" class="table table-bordered dt-responsive  doctorListTable"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%; ">
                        <thead class="table-light">
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Specialization</th>
                                <th>Contact Number</th>
                                <th>Schedule</th>
                                <th>Action</th>
                            </tr>
                        </thead><!-- end thead -->

                    </table> <!-- end table -->
                </div>
            </div>
        </div>
    </div>
</div>
@include('doctor-list.doctor-profile-form', compact('dayOfWeekOptions', 'specializations'))
<script>
    $(document).ready(function(e) {
        $('.doctorListTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-doctor-profile-list') }}",
                type: "POST",
            },
            columns: [{
                    data: 'firstname',
                    name: 'firstname'
                },
                {
                    data: 'lastname',
                    name: 'lastname'
                },
                {
                    data: 'specialization',
                    name: 'specialization'
                },
                {
                    data: 'contact_number',
                    name: 'contact_number'
                },
                {
                    data: 'schedule',
                    name: 'schedule'
                },
                {
                    data: 'action',
                    name: 'action'
                }

            ]
        });

        $('#addDoctorButton').on('click', function() {
            $('#doctorFormModal').modal('show');
        });

        $('#doctorFormModal').on('hide.bs.modal', function() {
            $('#doctorForm').trigger('reset');
            $('#scheduleContainer').html(`
         <div class="row mb-2 schedule-item align-items-center">
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label class="form-label">Day of Week:</label>
                    <select class="form-select" name="day_of_week[]">
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-2">
                    <label class="form-label">Start Time:</label>
                    <input type="time" class="form-control" name="start_time[]">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group mb-2">
                    <label class="form-label">End Time:</label>
                    <input type="time" class="form-control" name="end_time[]">
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-center justify-content-start gap-2 mt-4">
                <button type="button" class="btn btn-success add-schedule">+</button>
                <button type="button" class="btn btn-danger remove-schedule">-</button>
            </div>
         </div>`);
            $('#action_button').val('Submit');
        });

        $(document).on('click', '.editButton', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            console.log(id);
            $.ajax({
                url: "/doctor-profile/" + id + "/edit",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#firstname').val(data.data.firstname);
                    $('#lastname').val(data.data.lastname);
                    $('#email').val(data.data.email);
                    $('#contact_number').val(data.data.contact_number);
                    $('#license_number').val(data.data.license_number);
                    $('select[name="specialization_id"]').val(data.data.specialization_id);

                    $('#scheduleContainer').empty();
                    data.data.doctor_schedule.forEach(schedule => {
                        let scheduleRow = `
<div class="row mb-2 schedule-item align-items-center">
    <div class="col-md-4">
        <div class="form-group mb-2">
            <label class="form-label">Day of Week:</label>
            <select class="form-select" name="day_of_week[]">
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
    <div class="col-md-3">
        <div class="form-group mb-2">
            <label class="form-label">Start Time:</label>
            <input type="time" class="form-control" name="start_time[]" value="${schedule.start_time}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group mb-2">
            <label class="form-label">End Time:</label>
            <input type="time" class="form-control" name="end_time[]" value="${schedule.end_time}">
        </div>
    </div>
    <div class="col-md-2 d-flex align-items-center justify-content-start gap-2 mt-4">
        <button type="button" class="btn btn-success add-schedule">+</button>
        <button type="button" class="btn btn-danger remove-schedule">-</button>
    </div>
</div>`;

                        $('#scheduleContainer').append(scheduleRow);
                    });
                    $('#hidden_id').val(data.data.id);
                    $('#action_button').val('Update');
                    $('#doctorFormModal').modal('show');
                },
                error: function(data) {

                },
            });
        });
    })
</script>
@endsection