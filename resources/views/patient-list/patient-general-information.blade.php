<div class="d-flex align-items-center justify-content-between">
    <h5 class="mt-3">Address</h5>
    <button class="btn btn-light btn-sm waves-effect waves-light" id="editGeneralInformationButton"><i
            class="ri-edit-2-line"></i></button>
</div>

<ul class="list-group mb-3">
    <li class="list-group-item">
        <i class="fas fa-map-marker-alt"></i>
        <span>{{ $patientProfile->address }}</span>
    </li>

</ul>

<div class="d-flex align-items-center justify-content-between">
    <h5 class="card-title mt-3">Latest Vital Sign</h5>
    <button class="btn btn-light btn-sm waves-effect waves-light" id="addVitalSign"><i
            class=" ri-heart-add-line"></i></button>
</div>
<ul class="list-group mb-4">
    <li class="list-group-item">
        <span>Blood Pressure: </span>
        <span id="blodPressure"></span>
    </li>
    <li class="list-group-item">
        <span>Temperature: </span>
        <span id="temperature"></span>
    </li>
    <li class=" list-group-item">
        <span>Heart Rate: </span>
        <span id="heartRate"></span>
    </li>
    <li class="list-group-item">
        <span>Pulse Rate: </span>
        <span id="pulseRate"></span>
    </li>
    <li class="list-group-item">
        <span>Weight: </span>
        <span id="weight"></span>
    </li>
</ul>

<div class="d-flex align-items-center justify-content-between">
    <h5 class="card-title mt-3">Latest Medical Assesment</h5>
    <button class="btn btn-light btn-sm waves-effect waves-light" id="addVitalSign"><i
            class="ri-file-edit-line"></i></button>
</div>
<ul class="list-group">
    <li class="list-group-item">
        <span>Check By: </span>
        <span id="checkBy"></span>
    </li>
    <li class="list-group-item">
        <span>Chief Complaint: </span>
        <span id="chiefComplaint"></span>
    </li>
    <li class="list-group-item">
        <span>Assesment: </span>
        <span id="assesment"></span>
    </li>
    <li class=" list-group-item">
        <span>Treatment Plan: </span>
        <span id="treatmentPlan"></span>
    </li>
    <li class="list-group-item">
        <span>Notes: </span>
        <span id="notes"></span>
    </li>
</ul>

@include('patient-list.vital-sign-form')
@include('patient-list.patient-list-form-profile')
<script>
    function getLatestVitalSign(patientProfileId) {
        $.ajax({
            url: "/get-latest-vital-sign/" + patientProfileId,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            dataType: "json",
            success: function(data) {
                console.log(data);
                $('#blodPressure').text(`${data.data.blood_pressure} mmHg`);
                $('#temperature').text(`${data.data.temperature}°C`);
                $('#heartRate').text(`${data.data.heart_rate}BPM`);
                $('#pulseRate').text(`${data.data.pulse_rate}Hz`);
                $('#weight').text(`${data.data.weight}Kg`);
            },
            error: function(data) {

            },
        });
    }

    function getLatestMedicalAssesment(patientProfileId) {
        $.ajax({
            url: "/get-latest-medical-assesment/" + patientProfileId,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            dataType: "json",
            success: function(data) {
                console.log(data);
                $('#checkBy').text(`Dr. ${data.data.doctor_profile.firstname} ${data.data.doctor_profile.lastname}`)
                $('#chiefComplaint').html(`${data.data.chief_complaint.replace(/\n/g, '<br>')}`);
                $('#assesment').html(`${data.data.assesment.replace(/\n/g, '<br>')}`);
                $('#treatmentPlan').html(`${data.data.treatment_plan.replace(/\n/g, '<br>')}`);
                $('#notes').html(`${data.data.notes.replace(/\n/g, '<br>')}`);

            },
            error: function(data) {

            },
        });
    }

    $(document).ready(function() {

        $('#addVitalSign').on('click', function(e) {
            e.preventDefault();
            $('#patient_profile_id').val("{{$patientProfile->id}}")
            $('#vitalSignModal').modal('show');
        })


        getLatestVitalSign(`{{ $patientProfile->id }}`)
        getLatestMedicalAssesment(`{{ $patientProfile->id }}`)

        $('#vitalSignModal').on('hide.bs.modal', function() {
            $('#vitalSignForm').trigger('reset');
            $('#action_button').val('Submit');
        });

        $('#editGeneralInformationButton').on('click', function(e) {
            e.preventDefault();
            var id = '{{$patientProfile->id}}';
            console.log(id);
            $.ajax({
                url: "/patient-list/" + id + "/edit",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    $('#firstname').val(data.data.firstname);
                    $('#lastname').val(data.data.lastname);
                    $('#birthdate').val(data.data.birthdate);
                    $('#contact_number').val(data.data.contact_number);
                    if (data.data.is_pwd == 1) {
                        $('#pwdNumberDiv').removeAttr('hidden'); // Show PWD number field
                        $('#is_pwd').prop('checked', true); // Correctly set the checkbox
                        $('#pwd_number').val(data.data.pwd_number);
                    } else {
                        $('#pwdNumberDiv').attr('hidden', true); // Hide PWD number field
                        $('#is_pwd').prop('checked', false); // Uncheck if not PWD
                    }
                    $('#address').val(data.data.address);
                    $('#hidden_id').val(data.data.id);
                    $('#action_button').val('Update');
                    $('#dataModal').modal('show');
                },
                error: function(data) {

                },
            });
        });


    });
</script>