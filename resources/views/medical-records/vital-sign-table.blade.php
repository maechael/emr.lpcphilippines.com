<div class="card"
    style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
    <div class="card-body">
        <div class="mb-2 d-flex justify-content-between align-items-center">
            <div>
                <h5>Vital Sign</h5>
            </div>
            <!-- <button class="btn btn-success"
                id="uploadLabResults">
                UPLOAD</button> -->
        </div>
        <table id="vitalSignTable" class="table table-bordered dt-responsive  vitalSignTable"
            style="border-collapse: collapse; border-spacing: 0; width: 100%; ">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>BP</th>
                    <th>Temp</th>
                    <th>Heart Rate</th>
                    <th>Pulse Rate</th>
                    <th>Weight</th>

                </tr>
            </thead><!-- end thead -->

        </table> <!-- end table -->
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.vitalSignTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('get-vital-sign-table') }}",
                type: "POST",
                data: {
                    id: "{{ $patientProfile->id }}"
                }
            },
            columns: [{
                    data: 'vital_sign_date',
                    name: 'vital_sign_date'
                },
                {
                    data: 'blood_pressure',
                    name: 'blood_pressure'
                },
                {
                    data: 'temperature',
                    name: 'temperature'
                },
                {
                    data: 'heart_rate',
                    name: 'heart_rate'
                },
                {
                    data: 'pulse_rate',
                    name: 'pulse_rate'
                },
                {
                    data: 'weight',
                    name: 'weight'
                },


            ]
        });
    })
</script>