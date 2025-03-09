<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        @php
        $user = Auth::user();
        $id = $user->id;
        $adminData = App\Models\User::find($id);
        $userProfile = $user->userProfile;
        @endphp

        <!-- User details -->
        <div class="user-profile text-center mt-3">
            <div class="">
                <img src="{{ asset($userProfile->media->filepath) }}" alt=""
                    class="avatar-md rounded-circle">
            </div>
            <div class="mt-3">
                <h4 class="font-size-16 mb-1">{{ $userProfile->firstname }} {{ $userProfile->lastname }}</h4>
                <p class="mb-0"><em>{{ $userProfile->position }}</em></p>
                <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                    Online</span>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                @can('view_any_menu', App\Models\Permission::find(1))
                <li class="menu-title">Menu</li>

                @can('view_dashboard', App\Models\Permission::find(1))
                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>

                </li>
                @endcan

                @can('view_any_security', App\Models\Permission::find(1))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-lock-line"></i>
                        <span>Security</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('admin.user-profiles.index') }}">User Profile</a></li>
                        <li><a href="{{ route('admin.users.index') }}">Accounts</a></li>
                        <li><a href="{{ route('admin.roles.index') }}">Role</a></li>
                        <li><a href="{{ route('admin.permissions.index') }}">Permission</a></li>
                    </ul>
                </li>
                @endcan
                @endcan

                @can('view_any_patient_list', App\Models\Permission::find(1))
                <li class="menu-title">Patient Section</li>
                <li>
                    <a href="{{ route('patient-list.index') }}" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Patient List</span>
                    </a>

                </li>
                @endcan

                @can('view_doctor_section', App\Models\Permission::find(1))

                <li class="menu-title">Doctor Section</li>
                <li>

                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-quill-pen-line"></i>
                        <span>Specialization</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('specialization.index') }}">List</a></li>
                    </ul>

                </li>


                <li>

                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-quill-pen-line"></i>
                        <span>Doctor</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('doctor-profile.index') }}">List</a></li>
                    </ul>

                </li>
                @endcan

                @can('view_any_nurse', App\Models\Permission::find(1))
                <li class="menu-title">Nurse Section</li>
                <li>

                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-quill-pen-line"></i>
                        <span>Lab Type</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('lab-result-type.index') }}">List</a></li>
                    </ul>

                </li>
                @endcan

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->