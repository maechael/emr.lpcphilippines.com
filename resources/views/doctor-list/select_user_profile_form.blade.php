<div class="modal fade" id="userFormModal" tabindex="-1" aria-labelledby="userFormModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userFormModalLabel">Log Vital Sign</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="selectUserForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="form-group mb-2">
                                <label for="user_profile_id" class="form-label">Users</label>
                                <select name="user_profile_id" id="user_profile_id" class="form-control">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                    <option value='{{$user->id}}'>{{$user->lastname}}, {{$user->firstname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="doctor_profile_id" id="doctor_profile_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="action_button" id="action_button" value="Submit"
                        class="btn btn-primary ladda-button selectUserProfileButton" data-style="expand-right">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#selectUserForm').on('submit', function(e) {
            e.preventDefault();
            var userLaddaButton = Ladda.create($('.selectUserProfileButton')[0]);
            userLaddaButton.start();
            var formData = new FormData(this);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('assign-user-profile-to-doctor') }}",
                type: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from processing the data
                contentType: false, // Prevent jQuery from setting content-type
                dataType: "json",
                success: function(data) {
                    userLaddaButton.stop();
                    Swal.fire({
                        title: 'Success!',
                        text: 'User has been assigned!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $('#userFormModal').modal('hide');
                    });
                },
                error: function(data) {
                    userLaddaButton.stop();
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