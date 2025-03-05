<div class="row">
    <div class="col-12">
        <div class="card"
            style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
            <div class="card-body">
                <h4>Logs</h4>
                <div id="logContainer"
                    style="max-height: 300px; overflow-y: auto; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background-color: #f8f9fa;">
                    @foreach($logs as $log)
                    <div class="log-entry mb-2">
                        <p class="mb-1"><strong>{{ $log->userProfile->firstname }} {{ $log->userProfile->lastname }}</strong> - {{ $log->changes }}</p>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="card" style="background-color: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); border-radius: 10px; overflow: hidden;">
        <div class="card-body">
            @include('notes-log.index', compact('patientProfile', 'patientNotes', 'userProfileId'))
        </div>
    </div>
</div>