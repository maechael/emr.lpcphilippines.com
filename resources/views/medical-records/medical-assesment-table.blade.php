<div class="card"
    style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
    <div class="card-body">
        <div class="mb-2 d-flex justify-content-between align-items-center">
            <div>
                <h5>Medical Assesments</h5>
            </div>
            <button class="btn btn-success"
                id="addMedicalAssesment">
                LOG ASSESMENT</button>
        </div>
        <table id="medicalAssesmentTable" class="table table-bordered dt-responsive medicalAssesmentTable"
            style="border-collapse: collapse; border-spacing: 0; width: 100%; ">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Checked By:</th>
                    <th>Action</th>
                </tr>
            </thead><!-- end thead -->

        </table> <!-- end table -->
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.medicalAssesmentTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-medical-assesment-table') }}",
                type: "POST",
                data: {
                    id: "{{ $patientProfile->id }}"
                }
            },
            columns: [{
                    data: 'date_log',
                    name: 'date_log'
                },
                {
                    data: 'checked_by',
                    name: 'checked_by'
                },
                {
                    data: 'action',
                    name: 'action'
                }
            ]
        });

        $(document).on('click', '.editMedicalAssesmentButton', function() {
            var id = $(this).attr('id');
            window.location.href = `{{ route('medical_record.edit',':id') }}`.replace(':id', id);
        });

        $('#addMedicalAssesment').on('click', function() {
            window.location.href = `{{ route('medical_record.show',':id') }}`.replace(':id', '{{$patientProfile->id}}');
        });
    });
</script>