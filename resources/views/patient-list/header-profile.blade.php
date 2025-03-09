<div class="card"
    style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <!-- Avatar -->
            <div class="d-flex align-items-center">
                <!-- Avatar -->
                <img src="{{ asset($patientProfile->userProfile ? $patientProfile->userProfile->media->filepath : ' ' ) }}"
                    alt="Avatar"
                    class="rounded-circle me-2"
                    style="width: 50px; height: 50px; object-fit: cover;">

                <!-- Patient Details -->
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
            </div>

            <div class="d-flex">
                <!-- <div class="mr-2" style="margin-right: .5rem">
                    <button class="btn btn-outline-primary btn-sm btnEdit" id="editGeneralInformationButton"
                        name="edit" type="button">EDIT</button>
                </div>
                <div class="mr-2" style="margin-right: .5rem">
                    <button class="btn btn-outline-success btn-sm addProductButton" id="addProductButton"
                        name="addProduct" type="button">ADD PRODUCT</button>
                         ri-user-settings-lin
                </div> -->
                @can('view_account_setting', App\Models\PatientProfile::find(1))
                <button class="btn btn-small btn-light" id="addPatientAccountSetting"><i class="ri-user-settings-line"></i> Account Setting</button>
                @endcan
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
                    Laboratory
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#imaging" role="tab"
                    style="padding: 5px 10px; font-size: 14px;">
                    Imaging
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
                <a class="nav-link" data-bs-toggle="tab" href="#otherDocuments" role="tab"
                    style="padding: 5px 10px; font-size: 14px;">
                    Document
                </a>
            </li>
            @can('view_activity_log', App\Models\PatientProfile::find(1))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#activityLogs" role="tab"
                    style="padding: 5px 10px; font-size: 14px;">
                    Activity Logs
                </a>
            </li>
            @endcan
        </ul>
    </div>
</div>

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
                    <input type="hidden" name="patient_profile_id" id="patient_profile_id" value="{{$patientProfile->id}}">
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
                url: "{{ route('assign-user-profile-to-patient') }}",
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


        $('#addPatientAccountSetting').on('click', function() {
            $('#userFormModal').modal('show');
        });
    });
</script>