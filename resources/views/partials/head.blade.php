<meta name="csrf-token" content="{{ csrf_token() }}">
<!--Jquery extension -->
<script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>

{{-- ladda spinner button --}}
{{-- <link href="https://msurguy.github.io/ladda-bootstrap/dist/ladda-themeless.min.css" rel="stylesheet"> --}}

<!-- Bootstrap Css -->
<link href="{{ asset('backend/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ asset('backend/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ asset('backend/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- Sweet Alert CSS-->
<link href="{{ asset('backend/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />


<!--Css For Dropzone-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css" rel="stylesheet">
<script src="//editor.unlayer.com/embed.js"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
</script>
