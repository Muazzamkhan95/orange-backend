
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('admin.layouts.head')
</head>
<body class="app sidebar-mini rtl" >
	<div id="global-loader" ></div>
	<div class="page">
		<div class="page-main">
			<!-- Sidebar menu-->

			@include('admin.layouts.sidebar')

			<!-- app-content-->
			<div class="app-content ">
				<div class="side-app">
					<div class="main-content">
						<!-- Top navbar -->
                        @include('admin.layouts.navbar')
						<!-- Top navbar-->

						<!-- Page content -->
                        @yield('content')



						</div>
					</div>
				</div>
			</div>
			<!-- app-content-->
		</div>
	</div>
	<!-- Back to top -->
    @include('admin.layouts.footer')


</body>

</html>
