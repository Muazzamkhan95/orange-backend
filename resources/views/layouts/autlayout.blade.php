
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


	<!-- Favicon -->
	<link href="{{ asset('assets/img/brand/favicon.png') }}" rel="icon" type="image/png">

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel="stylesheet">

	<!-- Icons -->
	<link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
	<link href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ asset('dist/css/adminlte.min.css') }}" rel="stylesheet">
	<link href="{{ asset('dist/css/styles.css') }}" rel="stylesheet">
	{{-- <link href="{{ asset('') }}" rel="stylesheet">
	<link href="{{ asset('') }}" rel="stylesheet">
	<link href="{{ asset('') }}" rel="stylesheet">
	<link href="{{ asset('') }}" rel="stylesheet">
	<link href="{{ asset('') }}" rel="stylesheet"> --}}

</head>

<body class="hold-transition login-page">
    @yield('content')


	<!-- Ansta Scripts -->
	<!-- Core -->
	<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

</body>

</html>
