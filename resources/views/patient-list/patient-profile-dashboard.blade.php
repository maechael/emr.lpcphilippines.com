@extends('layouts.admin_master')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-lg mb-5 bg-white rounded">
                    <div class="card-body">
                        @include('patient-list.patient-general-information', compact('patientProfile'))
                    </div>
                </div>

            </div>
            <div class="col-md-8">
                @include('patient-list.header-profile', compact('patientProfile'))
                <div class="tab-content text-muted mt-3">
                    <div class="tab-pane fade show active" id="appointment" role="tabpanel">
                        @include('appointment.index', compact('specializations', 'patientProfile'))
                    </div>
                    <div class="tab-pane fade" id="labResults" role="tabpanel">
                        @include('lab-results.index', compact('patientProfile'))
                    </div>
                    <div class="tab-pane fade" id="medicalRecords" role="tabpanel">
                        @include('medical-records.index', compact('patientProfile'))
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection