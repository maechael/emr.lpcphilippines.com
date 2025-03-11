@extends('layouts.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <div class="card"
            style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
            <div class="card-header">
                <h4>Update Recor Medical Assestment for patient: {{$patientProfile->firstname}} {{$patientProfile->lastname}}</h4>
            </div>
            <div class="card-body">
                <form id="medicalAssesmentForm" enctype="multipart/form-data">
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="chief_complaint">Chief Complaint:</label>
                                <textarea class="form-control" rows="5" id="chief_complaint" name="chief_complaint">{{ $medicalRecord->chief_complaint }}</textarea>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="assesment" class="form-label">Assesment:</label>
                                <textarea class="form-control" rows="5" id="assesment" name="assesment">{{ $medicalRecord->assesment }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="treatment_plan" class="form-label">Treatment Plan:</label>
                                <textarea class="form-control" rows="5" id="treatment_plan" name="treatment_plan">{{ $medicalRecord->treatment_plan }}</textarea>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="notes" class="form-label">Notes:</label>
                                <textarea class="form-control" rows="5" id="notes" name="notes">{{ $medicalRecord->notes }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label for="doctor_profile_id" class="form-label">Checked By:</label>
                            <select class="form-select" name="doctor_profile_id">
                                @foreach($doctorList as $doctor)
                                <option value="{{ $doctor->id }}" {{ $doctor->id == $medicalRecord->doctor_profile_id ? 'selected' : '' }}>
                                    Dr. {{ $doctor->lastname }} {{ $doctor->firstname }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="patient_profile_id" id="patient_profile_id" value="{{$patientProfile->id}}">

                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <div></div>
                        <div>
                            <button type="button" class="btn btn-secondary" id="closeMedicalRecordButton">Close</button>
                            <input type="submit" name="action_button" id="action_button" value="Update"
                                class="btn btn-primary ladda-button patientlistButton" data-style="expand-right">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('#medicalAssesmentForm').on('submit', function(e) {
            e.preventDefault();
            var hiddenId = "{{$medicalRecord->id}}";
            var url = $('#action_button').val() == 'Update' ? `{{ route('medical_record.update',':id') }}`.replace(':id', hiddenId) : "{{ route('medical_record.store') }}";
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
                        text: method === 'PUT' ? 'Medical Record has been updated!' : 'Medical Record has been added!',
                        icon: 'success',
                        confirmButtonText: 'Ok'
                    }).then((result) => {

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

        $('#closeMedicalRecordButton').on('click', function(e) {
            window.history.back();
        });
    });
</script>
@endsection