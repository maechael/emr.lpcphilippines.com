@php
    use App\Models\Lead;
    use App\Models\Callback;
    use Carbon\Carbon;
    use App\Models\UserProfile;
@endphp
<style>
    .custom-notification-dropdown .dropdown-menu {
        width: 400px !important;
        /* Use !important to enforce the width */
        min-width: 400px !important;
        /* Ensure the width doesn't shrink */
        max-width: 400px !important;
        /* Prevent it from growing too wide */
    }

    .custom-notification-dropdown .notification-item h6 {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 280px;
        /* Adjust this to fit within your design */
    }

    .custom-notification-dropdown .notification-item .text-muted {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 280px;
        /* Adjust accordingly */
    }

    .custom-notification-dropdown .avatar-xs {
        width: 35px;
        height: 35px;
    }

    .custom-notification-dropdown .avatar-title {
        font-size: 16px;
    }

    .custom-notification-dropdown [data-simplebar] {
        overflow-y: auto;
        max-height: 230px;
    }

    .custom-notification-dropdown .notification-item .d-flex {
        align-items: center;
    }

    .custom-notification-dropdown .notification-item .flex-1 {
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<div class="dropdown d-inline-block custom-notification-dropdown">
    <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
        data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ri-notification-3-line"></i>
        <span class="noti-dot"></span>
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
        aria-labelledby="page-header-notifications-dropdown">
        <div class="p-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0"> Notifications </h6>
                </div>
                <div class="col-auto">
                    <a href="#!" class="small"> View All</a>
                </div>
            </div>
        </div>
        <div data-simplebar style="max-height: 230px;">

            @php
                $user = Auth::user();
                $notifications = $user->notifications->sortByDesc('created_at');
                $userProfile = $user->userProfile;
                // $todayCount = 5;
            @endphp
            @foreach ($notifications as $notification)
                @if ($notification->type == 'App\Notifications\AssignAppointedLead')
                    <a href="" class="text-reset notification-item">
                        <div class="d-flex">
                            <div class="avatar-xs me-3">
                                <span class="avatar-title bg-success rounded-circle font-size-16">
                                    <i class="mdi mdi-account-arrow-left-outline"></i>
                                </span>
                            </div>

                            <div class="flex-1">
                                <h6 class="mb-1">
                                    {{ $notification->data['message'] ?? 'Default message' }}
                                </h6>
                                <div class="font-size-12 text-muted">
                                    <!-- Additional text or elements can go here if needed -->
                                </div>
                            </div>
                        </div>
                    </a>
                @elseif ($notification->type == 'App\Notifications\ReassignAppointedLeadNotification')
                    <a href="" class="text-reset notification-item">
                        <div class="d-flex">
                            <div class="avatar-xs me-3">
                                <span class="avatar-title bg-info rounded-circle font-size-16">
                                    <i class="mdi mdi-account-arrow-right-outline"></i>
                                </span>
                            </div>

                            <div class="flex-1">
                                <h6 class="mb-1">
                                    {{ $notification->data['message'] ?? 'Default message' }}
                                </h6>
                                <div class="font-size-12 text-muted">
                                    <!-- Additional text or elements can go here if needed -->
                                </div>
                            </div>
                        </div>
                    </a>
                @elseif($notification->type == 'App\Notifications\LeadNotesNotification')
                    @php
                        $imagePath = UserProfile::find($notification->data['sender'])->media->filepath;
                    @endphp
                    {{-- <pre>{{ dump($imagePath) }}</pre> --}}
                    <a href="{{ route('appointed-list-profile-view', $notification->data['lead_id']) }}"
                        class="text-reset notification-item ">
                        <div class="d-flex">
                            <div class="avatar-xs me-3">
                                {{-- <span class="avatar-title bg-success rounded-circle font-size-16">
                                    <i class="mdi mdi-account-arrow-right-outline"></i>
                                </span> --}}
                                <img src="{{ asset($imagePath) }}" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                            </div>

                            <div class="flex-1">
                                <h6 class="mb-1">
                                    {{ $notification->data['title'] ?? 'Default message' }}
                                </h6>
                                <div class="font-size-12 text-muted">
                                    {{ $notification->data['description'] ?? 'Default message' }}
                                </div>
                            </div>
                        </div>
                    </a>
                @elseif($notification->type == 'App\Notifications\GeneralNotification')
                    @php
                        $imagePath = UserProfile::find($notification->data['notifyBy'])->media->filepath;
                    @endphp
                    {{-- <pre>{{ dump($imagePath) }}</pre> --}}
                    <a href="{{ url($notification->data['link']) }}" class="text-reset notification-item ">
                        <div class="d-flex">
                            <div class="avatar-xs me-3">
                                {{-- <span class="avatar-title bg-success rounded-circle font-size-16">
                                    <i class="mdi mdi-account-arrow-right-outline"></i>
                                </span> --}}
                                <img src="{{ asset($imagePath) }}" class="me-3 rounded-circle avatar-xs" alt="user-pic">
                            </div>

                            <div class="flex-1">
                                <h6 class="mb-1">
                                    {{ $notification->data['title'] ?? 'Default message' }}
                                </h6>
                                <div class="font-size-12 text-muted">
                                    {{ $notification->data['description'] ?? 'Default message' }}
                                </div>
                            </div>
                        </div>
                    </a>
                @elseif($notification->type == 'App\Notifications\AssignPolicyForRenewalNotification')
                    {{-- <pre>{{ dump($imagePath) }}</pre> --}}
                    <a href="" class="text-reset notification-item ">
                        <div class="d-flex">
                            <div class="avatar-xs me-3">
                                <span class="avatar-title bg-success rounded-circle font-size-16">
                                    <i class="mdi mdi mdi-autorenew"></i>
                                </span>
                            </div>

                            <div class="flex-1">
                                <h6 class="mb-1">
                                    {{ 'Policy Renewal' }}
                                </h6>
                                <div class="font-size-12 text-muted">
                                    {{ $notification->data['message'] ?? 'Default message' }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            @endforeach
        </div>
        <div class="p-2 border-top">
            <div class="d-grid">
                <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                    <i class="mdi mdi-arrow-right-circle me-1"></i> View More..
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    var userId = {{ auth()->id() }}
</script>
