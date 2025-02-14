<div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModalLabel">Add Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="patientListForm">
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
                                <label for="birthdate" class="form-label">Birthdate:</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" placeholder="Birthdate">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="contact_number" class="form-label">Contact Number:</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Contact Number">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label class="form-label">Pwd:</label>
                            <div class="square-switch">
                                <input type="checkbox" id="is_pwd" switch="info"
                                    name="is_pwd">
                                <label for="is_pwd" data-on-label="Yes" data-off-label="No"></label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2" id="pwdNumberDiv" hidden>
                                <label for="pwd_number" class="form-label">Pwd Number:</label>
                                <input type="text" class="form-control" id="pwd_number" name="pwd_number" placeholder="Pwd Number">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="address">Address:</label>
                                <textarea class="form-control" rows="5" placeholder="Type a address..." id="address" name="address"></textarea>
                            </div>
                        </div>
                    </div>

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
        $('#is_pwd').on('change', function() {
            if ($(this).prop('checked')) {
                $('#pwdNumberDiv').removeAttr('hidden'); // Show PWD number field
            } else {
                $('#pwdNumberDiv').attr('hidden', true); // Hide PWD number field
            }
        });

        $('#patientListForm').on('submit', function(e) {
            e.preventDefault();
            var url = "{{ route('patient-list.store') }}";
            var method = 'POST';
            var formData = new FormData(this);
            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false, // Prevent jQuery from processing the data
                contentType: false,
                dataType: "json",
                success: function(data) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Tools Equipment Policy has been added!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $('#dataModal').modal('hide');
                        $('.patientListTable').DataTable().ajax.reload();
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
            })

        });
    });
</script>