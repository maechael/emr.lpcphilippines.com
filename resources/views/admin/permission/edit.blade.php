{{-- <div class="modal fade" id="updatePermissionModal" tabindex="-1" aria-labelledby="updatePermissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="updatePermissionModalLabel">Edit Permission</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" id="updatePermissionForm" action="{{ route('admin.permissions.update'), $permission }}" >
            @csrf
            @method('PUT')
            <div class="modal-body">
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="hidden" id="permissionId" name="permissionId" value="{{ $permissions->id }}">
                <input type="text" class="form-control" id="name" name="name" >
              </div>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" id ="submitBtn" class="btn btn-primary">Submit</button>
          </div>
        </form>
        </div>
      </div>
    </div>

  </div>
  <script>
  $(document).ready(function () {
    var updatePermissionModal = document.getElementById('updatePermissionModal');
    var permissionId; // Move the permissionId variable outside the event listener

    updatePermissionModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        permissionId = button.getAttribute('data-permission-id'); // Update the permissionId variable
        var permissionName = button.getAttribute('data-permission-name');
        const labelElement = document.getElementById('name');
        labelElement.value = permissionName;
        $('#permissionId').val(permissionId);

    });

    $('#updatePermissionForm').submit(function(event){
      event.preventDefault();
      console.log('check')
      var permissionId = $('#permissionId').val();
      var permissionName = $('#name').val();
      var csrfToken = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
            type: 'POST',
            url: formAction,
            data: {
            _token: csrfToken, // Include the CSRF token
            userId: permissionId, // Send the userId as well
            permissionName: permissionName
            },
            success: function(response) {
                // Close the modal and refresh the page
                $('#updatePermissionModal').modal('hide');
                location.reload();
            },
            error: function(xhr, status, error) {
             console.error('AJAX error:', error);
        }
      });
    })
});

   </script> --}}
