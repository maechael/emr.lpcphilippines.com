<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">


        <!-- User details -->
        <div class="user-profile text-center mt-3">
            <div class="">
                <img src="{{ asset('backend/assets/images/users/Elchico(256x256).png') }}" alt=""
                    class="avatar-md rounded-circle">
            </div>
            <div class="mt-3">
                <h4 class="font-size-16 mb-1">Maechael Elchico</h4>
                <p class="mb-0"><em>Web Developer</em></p>
                <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i>
                    Online</span>
            </div>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>
                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="ri-dashboard-line"></i><span class="badge rounded-pill bg-success float-end">3</span>
                        <span>Dashboard</span>
                    </a>

                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-lock-line"></i>
                        <span>Security</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">

                        <li><a href="{{ route('admin.roles.index') }}">Role</a></li>
                        <li><a href="{{ route('admin.permissions.index') }}">Permission</a></li>
                    </ul>
                </li>
                @can('view_any_patient_list', App\Models\Permission::find(1))
                <li class="menu-title">Patient Section</li>
                <li>
                    <a href="{{ route('patient-list.index') }}" class="waves-effect">
                        <i class="ri-dashboard-line"></i><span class="badge rounded-pill bg-success float-end">3</span>
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

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->