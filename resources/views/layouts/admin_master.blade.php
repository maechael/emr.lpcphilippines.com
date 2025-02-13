@include('partials.main')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            transform: scale(0.9) !;
            transform-origin: 0 0;
        }
    </style>

    {{-- preloader --}}
    @include('layouts.preloader')

    {{-- header css --}}

    @include('partials.head')

    <!--Jquery extension -->
    <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>

    <!-- Push Nofificatiaon-->
    <script src="{{ asset('js/push.min.js') }}"></script>

    <!-- Sweet Alert CSS-->
    <link href="{{ asset('backend/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

    {{-- ladda spinner button --}}
    <link href="{{ asset('backend/assets/libs/ladda-bootstrap/dist/ladda-themeless.min.css') }}" rel="stylesheet">
    <script src="{{ asset('backend/assets/libs/ladda-bootstrap/dist/spin.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/ladda-bootstrap/dist/ladda.min.js') }}"></script>

    @include('partials.vendor-scripts')

    <!-- App favicon -->
    {{-- <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.icon') }}"> --}}
    <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Add the evo-calendar.css for styling -->
    <link href="{{ asset('backend/assets/libs/evo-calendar/css/evo-calendar.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('backend/assets/libs/evo-calendar/css/evo-calendar.royal-navy.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- DataTables -->
    <link href="{{ asset('backend/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />


    <link href="{{ asset('backend/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" />

    <!-- Responsive Table css -->
    <link href="{{ asset('backend/assets/libs/admin-resources/rwd-table/rwd-table.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Plugin css -->
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/@fullcalendar/core/main.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/@fullcalendar/daygrid/main.min.css') }}"
        type="text/css">
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/@fullcalendar/bootstrap/main.min.css') }}"
        type="text/css">
    <link rel="stylesheet" href="{{ asset('backend/assets/libs/@fullcalendar/timegrid/main.min.css') }}"
        type="text/css">

    @include('partials.head')

    <!-- JAVASCRIPT -->
    {{-- <script src="{{ mix('js/app.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/bootstrap.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/jquery.min.js') }}"></script> --}}

    {{-- <script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script> --}}

    <script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> --}}
    <script src="{{ asset('backend/assets/libs/select2/js/select2.min.js') }}"></script>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('backend/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/node-waves/waves.min.js') }}"></script>



</head>

@include('partials.body')

<!-- <body data-layout="horizontal" data-topbar="dark"> -->

<!-- Begin page -->
<div id="layout-wrapper">

    @include('partials.menu')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        {{--        <div id="notification" class="alert alert-success mx-3">test</div> --}}
        @yield('admin')
        <!-- End Page-content -->

        @include('partials.footer')


    </div>
    <!-- end main content--

<! END layout-wrapper -->
</div>


<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>

<!-- jquery.vectormap map -->
<script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}">
</script>
<script src="{{ asset('backend/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}">
</script>

<!-- Required datatable js -->
{{-- <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script> --}}
<script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>


<!-- Buttons examples -->
<script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>

<script src="{{ asset('backend/assets/libs/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>

<script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>


<!-- Responsive examples -->
<script src="{{ asset('backend/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}">
</script>
<script src="{{ asset('backend/assets/js/pages/dashboard.init.js') }}"></script>
<script src="{{ asset('backend/assets/libs/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/pages/form-editor.init.js') }}"></script>


<!-- Responsive Table js -->
<script src="{{ asset('backend/assets/libs/admin-resources/rwd-table/rwd-table.min.js') }}"></script>

<!-- Init js -->
<script src="{{ asset('backend/assets/js/pages/table-responsive.init.js') }}"></script>

<!-- Datatable init js -->
<script src="{{ asset('backend/assets/js/pages/datatables.init.js') }}"></script>

<script src="{{ asset('backend/assets/libs/evo-calendar/js/evo-calendar.js') }}"></script>

<!-- App js -->
<script src="{{ asset('backend/assets/js/app.js') }}"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

{{-- form validation style --}}
<link href="{{ asset('backend/assets/libs/spectrum-colorpicker2/spectrum.min.css') }}" rel="stylesheet"
    type="text/css">
<link href="{{ asset('backend/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}"
    rel="stylesheet">

<script href="{{ asset('backend/assets/libs/spectrum-colorpicker2/spectrum.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/parsleyjs/parsley.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/pages/form-validation.init.js') }}"></script>
{{-- <script src="{{ asset('backend/assets/js/pages/form-advanced.init.js') }}"></script> --}}
<script src="{{ asset('backend/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>


<!-- Sweet Alerts js -->
<script src="{{ asset('backend/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<!-- Sweet alert init js-->
<script src="{{ asset('backend/assets/js/pages/sweet-alerts.init.js') }}"></script>

<!-- form mask -->
<script src="{{ asset('backend/assets/libs/inputmask/jquery.inputmask.min.js') }}"></script>

<!-- form mask init -->
<script src="{{ asset('backend/assets/js/pages/form-mask.init.js') }}"></script>


<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

</body>

</html>
