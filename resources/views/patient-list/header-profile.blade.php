<div class="card"
    style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <div style="font-size: 14px;">
                <h5 style="margin-bottom: 5px;">
                    {{$patientProfile->lastname}}, {{$patientProfile->firstname}}
                </h5>
                <div>Contact Number: {{$patientProfile->contact_number}}</div>
                <div>Age: {{$patientProfile->age}}</div>
                @if($patientProfile->is_pwd == true)
                <div>Pwd Number: {{$patientProfile->pwd_number}}</div>
                @endif

            </div>
            <div class="d-flex">
                <!-- <div class="mr-2" style="margin-right: .5rem">
                    <button class="btn btn-outline-primary btn-sm btnEdit" id="editGeneralInformationButton"
                        name="edit" type="button">EDIT</button>
                </div>
                <div class="mr-2" style="margin-right: .5rem">
                    <button class="btn btn-outline-success btn-sm addProductButton" id="addProductButton"
                        name="addProduct" type="button">ADD PRODUCT</button>
                </div> -->
            </div>
        </div>

        <ul class="nav nav-tabs nav-tabs-custom nav-justified mb-0" role="tablist" style="margin-top: 0.5rem;">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#appointment" role="tab"
                    style="padding: 5px 10px; font-size: 14px;">
                    Appointment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#labResults" role="tab"
                    style="padding: 5px 10px; font-size: 14px;">
                    Lab Results
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#medicalRecords" role="tab"
                    style="padding: 5px 10px; font-size: 14px;">
                    Medical Records
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#medication" role="tab"
                    style="padding: 5px 10px; font-size: 14px;">
                    Medication
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#fluidMonitoring" role="tab"
                    style="padding: 5px 10px; font-size: 14px;">
                    Fluid Monitoring
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#activityLogs" role="tab"
                    style="padding: 5px 10px; font-size: 14px;">
                    Activity Logs
                </a>
            </li>
        </ul>
    </div>
</div>


<script>

</script>