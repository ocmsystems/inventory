<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content={{csrf_token()}}>

    <title>
        @lang('global.app_name')
    </title>
    <link rel="stylesheet" href="/css/app.css"></link>
    <link rel="stylesheet" href="/dist/plugins/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <!-- Theme style -->
    <link rel="stylesheet" href="/dist/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/dist/css/adminlte.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/dist/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/dist/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/dist/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/dist/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/dist/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/dist/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- Google Font: Source Sans Pro -->
    <!-- IonIcons -->


    <!-- Datatables -->
    <link rel="stylesheet" href="/dist/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="/dist/plugins/datatables/dataTables.bootstrap4.css">
    
    

    <!-- jQuery -->
    <script src="/dist/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="/dist/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE -->
    <script src="/dist/js/adminlte.js"></script>

    
    <!-- Datatables -->
    <script src="/dist/plugins/datatables/jquery.dataTables.js"></script>
    <script src="/dist/plugins/datatables/dataTables.bootstrap4.js"></script>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse">
    @guest @yield('content') @else
    <div class="wrapper" id="app">
        <!-- Header -->
    @include('layouts.header')
        <!-- Sidebar -->
    @include('layouts.sidebar') 

    <div class="content-wrapper">
        @yield('content')
    </div>
    
    <!-- Footer -->
    @include('layouts.footer')
    </div>
    <!-- ./wrapper -->
    @endguest @yield('javascript')
    
</body>
</html>