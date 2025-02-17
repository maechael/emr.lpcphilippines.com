<div class="modal fade" id="doctorFormModal" tabindex="-1" aria-labelledby="doctorFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="doctorFormModalLabel">Add Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="doctorForm" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="firstname" class="form-label">Firstname:</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Firstname">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="lastname" class="form-label">Lastname:</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="email" class="form-label">Email:</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="contact_number" class="form-label">Contact Number:</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Contact Number">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="license_number" class="form-label">License Number:</label>
                                <input type="text" class="form-control" id="license_number" name="license_number" placeholder="License Number">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label">Specializaion:</label>
                                <select class="form-select" name="specialization_id">
                                    @foreach($specializations as $specialization)
                                    <option value="{{ $specialization->id }}">{{ $specialization->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div>
                            <label for="schedule" class="form-label">Schedule:</label>
                        </div>
                    </div>
                    <div id="scheduleContainer">
                        <div class="row mb-2 schedule-item align-items-end">
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label class="form-label">Day of Week:</label>
                                    <select class="form-select" name="day_of_week[]">
                                        @foreach($dayOfWeekOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label class="form-label">Start Time:</label>
                                    <input type="time" class="form-control" name="start_time[]">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-2">
                                    <label class="form-label">End Time:</label>
                                    <div class="d-flex">
                                        <input type="time" class="form-control me-2" name="end_time[]">
                                        <button type="button" class="btn btn-success add-schedule">Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <input type="hidden" name="hidden_id" id="hidden_id">
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
        $('#doctorForm').on('submit', function(e) {
            e.preventDefault();
            var hiddenId = $('#hidden_id').val();
            var url = $('#action_button').val() == 'Update' ? `{{ route('doctor-profile.update',':id') }}`.replace(':id', hiddenId) : "{{ route('doctor-profile.store') }}";
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
                        text: method === 'PUT' ? 'Doctor Profile has been updated!' : 'DoctorProfile has been added!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $('#doctorFormModal').modal('hide');
                        $('.doctorListTable').DataTable().ajax.reload();
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

        $('#scheduleContainer').on('click', '.add-schedule', function() {
            let newRow = $(this).closest('.schedule-item').clone();
            newRow.find('input').val('');
            if (!$('#action_button').val() == 'Update') {
                newRow.find('.add-schedule').removeClass('btn-success add-schedule').addClass('btn-danger remove-schedule').text('Remove');
            }
            $('#scheduleContainer').append(newRow);
        });

        $('#scheduleContainer').on('click', '.remove-schedule', function() {
            $(this).closest('.schedule-item').remove();
        });

    });
</script>