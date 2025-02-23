<div class="modal fade" id="assignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignRoleModalLabel">Assign Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.users.assignRole', $user) }}" id="assigRoleForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="userId" name="userId" value="{{ $user->id }}">
                    <div class="form-group">
                        <label for="role_id" class="col-form-label">Role{{ $user }}</label>
                        <select class="form-select" id="role_id" name="role_id">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        var assignRoleModal = document.getElementById('assignRoleModal');
        assignRoleModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            // Extract the user ID from the button's data-user-id attribute
            var userId = button.getAttribute('data-user-id');
            var roleId = button.getAttribute('data-role-id');
            // var currentRoleId = $('#role_id').data('current-role');
            $('#userId').val(userId);
            var roles = {{ Js::from($roles) }};
            let userRole = roles.filter(obj => obj.id == roleId);
            let roleLabel = userRole[0].id;
            var role_id = $('#role_id').find(":selected").val();
            role_id = roleLabel;
            $('#role_id').val(role_id);
        })
        $('#assignRoleForm').submit(function(event) {
            event.preventDefault();

            // Get the user ID and selected role
            var userId = $('#userId').val();
            var role = $('#role_id').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'POST',
                url: formAction,
                data: {
                    _token: csrfToken, // Include the CSRF token
                    userId: userId, // Send the userId as well
                    role: role
                },
                success: function(response) {
                    // Close the modal and refresh the page
                    $('#assignRoleModal').modal('hide');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                }
            });

        })
    })
</script>
