<div class="modal fade" id="specializationModal" tabindex="-1" aria-labelledby="specializationModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="specializationModalLabel">Specialization</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="specializationForm" enctype="multipart/form-data">
                <div class="modal-body">

                    <div class="row mb-2">

                        <div class="form-group mb-2">
                            <label for="name" class="form-label">Name:</label>
                            <input id="name" type="text" name="name" class="form-control">
                        </div>

                    </div>
                </div>
                <input type="hidden" name="hidden_id" id="hidden_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Close</button>
                    <input type="submit" name="action_button" id="action_button" value="Submit"
                        class="btn btn-primary ladda-button " data-style="expand-right">
                </div>
            </form>

        </div>

    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        $('#specializationForm').on('submit', function(e) {
            e.preventDefault();
            var hiddenId = $('#hidden_id').val();
            var url = $('#action_button').val() == 'Update' ? `{{ route('specialization.update',':id') }}`.replace(':id', hiddenId) : "{{ route('specialization.store') }}";
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
                        text: method === 'PUT' ? 'Specialization has been updated!' : 'Specialization has been added!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        $('#specializationModal').modal('hide');
                        $('.specializationTable').DataTable().ajax.reload();
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


    });
</script>