@extends('layouts.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card shadow-lg mb-5 bg-white rounded">
                    <div class="card-body">
                        @include('patient-list.patient-general-information', compact('patientProfile'))
                    </div>
                </div>

            </div>
            <div class="col-8">
                @include('patient-list.header-profile', compact('patientProfile'))
                <div class="tab-content text-muted mt-3">
                    <div class="tab-pane fade show active" id="appointment" role="tabpanel">
                        @include('appointment.index', compact('specializations', 'patientProfile'))
                    </div>
                    <div class="tab-pane fade" id="labResults" role="tabpanel">
                        @include('lab-results.index', compact('patientProfile', 'labTestTypes'))
                        @include('lab-results.lab-imaging', compact('patientProfile', 'labTestTypes'))
                    </div>
                    <div class="tab-pane fade" id="medicalRecords" role="tabpanel">
                        @include('medical-records.index', compact('patientProfile'))
                    </div>
                    <div class="tab-pane fade" id="medication" role="tabpanel">
                        @include('patient-medication.index', compact('patientProfile','dayOfWeekOptions'))
                    </div>
                    <div class="tab-pane fade" id="fluidMonitoring" role="tabpanel">
                        @include('patient-fluid-monitoring.patient-fluid-output', compact('patientProfile'))
                        @include('patient-fluid-monitoring.patient-fluid-intake', compact('patientProfile'))
                    </div>
                    <div class="tab-pane fade" id="activityLogs" role="tabpanel">
                        @include('patient-list.activity-logs', compact('patientProfile', 'logs'))
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection