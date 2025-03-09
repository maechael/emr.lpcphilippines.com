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
                @include('patient-list.header-profile', compact('patientProfile', 'users'))
                <div class="tab-content text-muted mt-3">
                    <div class="tab-pane fade show active" id="appointment">
                        <div class="tab-loader d-none">Loading...</div>
                        <div class="tab-content-container">
                            @include('appointment.index', compact('patientProfile'))
                        </div>
                    </div>
                    <div class="tab-pane fade" id="labResults">
                        <div class="tab-loader d-none">Loading...</div>
                        <div class="tab-content-container"></div>
                    </div>
                    <div class="tab-pane fade" id="medicalRecords">
                        <div class="tab-loader d-none">Loading...</div>
                        <div class="tab-content-container"></div>
                    </div>
                    <div class="tab-pane fade" id="medication">
                        <div class="tab-loader d-none">Loading...</div>
                        <div class="tab-content-container"></div>
                    </div>
                    <div class="tab-pane fade" id="imaging">
                        <div class="tab-loader d-none">Loading...</div>
                        <div class="tab-content-container"></div>
                    </div>
                    <div class="tab-pane fade" id="fluidMonitoring">
                        <div class="tab-loader d-none">Loading...</div>
                        <div class="tab-content-container"></div>
                    </div>
                    <div class="tab-pane fade" id="activityLogs">
                        <div class="tab-loader d-none">Loading...</div>
                        <div class="tab-content-container"></div>
                    </div>
                    <div class="tab-pane fade" id="otherDocuments">
                        <div class="tab-loader d-none">Loading...</div>
                        <div class="tab-content-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var activeTab = localStorage.getItem('activeTab') || 'appointment';

        function loadTabContent(tabId, patientId) {
            console.log(tabId, patientId);
            var tabPane = $('#' + tabId);
            var loader = tabPane.find('.tab-loader');
            var contentContainer = tabPane.find('.tab-content-container');

            // Check if content is already loaded
            if (contentContainer.html().trim() !== '') return;

            // Show loader
            loader.removeClass('d-none');

            $.ajax({
                url: "{{ route('tab.load') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    tab: tabId,
                    patient_id: patientId
                },
                success: function(response) {
                    console.log(response);
                    loader.addClass('d-none');
                    contentContainer.html(response);
                },
                error: function() {
                    loader.addClass('d-none');
                    contentContainer.html('<p class="text-danger">Failed to load content.</p>');
                }
            });
        }

        // Restore active tab from localStorage
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            var tabId = $(e.target).attr('href').replace('#', '');

            var patientId = "{{ $patientProfile->id }}";
            loadTabContent(tabId, patientId);

            // Save to localStorage
            localStorage.setItem('activeTab', tabId);
        });

        // Load the stored active tab on page load
        loadTabContent(activeTab, "{{ $patientProfile->id }}");

    });
</script>




@endsection