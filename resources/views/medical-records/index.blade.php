<div class="row">
    <div class="col-12">
        <div class="row">
            <div>
                @include('medical-records.medical-assesment-table', compact('patientProfile'))
            </div>
        </div>
        <div class="row mb-2">
            <div>
                @include('medical-records.vital-sign-table', compact('patientProfile'))
            </div>
        </div>
    </div>
</div>