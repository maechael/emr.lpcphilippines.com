<style>
    .ps-container {
        position: relative;
        overflow-y: auto !important;
        height: 400px;
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .media {
        display: flex;
        align-items: flex-start;
        margin-bottom: 6px;
        /* Reduced margin for closer messages */
        padding: 4px 6px;
        /* Minimal padding for tighter look */
        background-color: #fff;
        border-radius: 6px;
        /* Small radius for slight rounding */
        border: 1px solid #ddd;
        max-width: fit-content;
        /* Width adjusts to content */
        word-wrap: break-word;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .media-chat-reverse {
        flex-direction: row-reverse;
        margin-left: auto;
        padding: 4px 6px;
        /* Minimal padding for reversed messages */
        background-color: #e0e7ff;
        color: #333;
        border-radius: 6px;
        /* Matching small radius */
        border: 1px solid #c3dafe;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        max-width: fit-content;
        /* Width adjusts to content */
    }

    .media-chat-warning {
        background-color: #ffcccc;
        /* Light red background for non-regular notes */
        border: 1px solid #ff9999;
        /* Border color to match */
    }

    .avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        margin-left: 5px;
        /* Reduced margin for tighter alignment */
        background-color: #f5f6f7;
        color: #8b95a5;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        text-transform: uppercase;
    }

    .media-body {
        display: flex;
        flex-direction: column;
        max-width: 100%;
        /* Allow full width for text content */
    }

    .media-body h5 {
        margin: 0;
        font-size: 14px;
        font-weight: bold;
        color: inherit;
        margin-bottom: 2px;
    }

    .media-body p {
        margin: 0;
        font-size: 14px;
        color: inherit;
        margin-bottom: 2px;
        /* Minimal margin for compactness */
    }

    .media-body small {
        font-size: 12px;
        color: inherit;
    }

    .publisher {
        display: flex;
        align-items: center;
        padding: 10px;
        background-color: #f9fafb;
        border-top: 1px solid #ebebeb;
        gap: 10px;
    }

    .publisher-input {
        flex-grow: 1;
        border: 1px solid #ddd;
        padding: 6px;
        border-radius: 4px;
        background-color: #fff;
        font-size: 14px;
    }
</style>
<div id="notesDiv">
    <div class="ps-container mb-2" id="chat-content">
        @foreach ($patientNotes as $note)
        <div
            class="media {{ $userProfileId == $note->userProfile->id ? 'media-chat-reverse' : 'media-chat' }} {{ $note->status != 'regular' ? 'media-chat-warning' : '' }}">
            @if ($userProfileId != $note->userProfile->id)
            <div class="avatar">
                <img src="{{ asset($note->userProfile->media->filepath) }}" alt="User Avatar"
                    style="width: 100%; height: 100%; border-radius: 50%;">
            </div>
            @endif
            <div class="media-body">
                <p>{!! $note->description !!}</p>
                <small>
                    <i class="fa fa-clock-o"></i> {{ date('M d, Y H:i', strtotime($note->created_at)) }}
                </small>
            </div>
        </div>
        @endforeach
    </div>
    <div class="publisher">
        <textarea class="publisher-input" placeholder="Write something" id="noteDescription"
            style="height: 120px; resize: vertical;"></textarea>
        <button class="btn btn-primary btn-rounded waves-effect waves-light publisher-btn ladda-button" id="logNote"
            data-style="expand-right"><i class="fa fa-paper-plane"></i></button>
    </div>
</div>
<script>
    $(document).ready(function() {

        $('#logNote').on('click', function() {
            var description = $('#noteDescription').val().replace(/\n/g, '<br>');

            var logNoteLaddaButton = Ladda.create(this);
            logNoteLaddaButton.start();

            $.ajax({
                url: "{{ route('notes-log.store') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                data: {
                    description: description,
                    status: 'regular',
                    patient_profile_id: "{{$patientProfile->id}}"
                },
                success: function(data) {
                    logNoteLaddaButton.stop();
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    logNoteLaddaButton.stop();
                    if (jqXHR.status == 422) {
                        const errors = jqXHR.responseJSON.errors;
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').empty();
                        for (let field in errors) {
                            const errorMessage = errors[field];
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}Error`).text(errorMessage).show();
                        }
                    }
                },
            });

        });
    });
</script>