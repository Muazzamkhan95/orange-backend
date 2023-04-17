<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('admin.layouts.head')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake"
                src="{{ asset('/') }}{{ \App\Models\BusinessSetting::where(['type' => 'loader_gif'])->pluck('value')[0] }}"
                alt="AdminLTELogo" height="60" width="60">
        </div>
        @include('admin.layouts.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                @yield('content')
            </div>
        </div>
        <!-- /.content-header -->
        <!-- Back to top -->
        @include('admin.layouts.footer')

    </div>
</body>

</html>
