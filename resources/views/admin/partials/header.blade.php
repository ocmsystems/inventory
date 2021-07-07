<!DOCTYPE html>
<html lang="en">

<head>

      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="x-ua-compatible" content="ie=edge">
      <meta name="csrf-token" content={{csrf_token()}}>
      
      <title>
            @lang('global.app_name')
      </title>
      <!-- <link rel="apple-touch-icon" sizes="128x128" href="/img/logo_tigerph_mobile.png"> -->
      <!-- <link rel="icon" sizes="192x192" href="/img/logo_tigerph_192.png"> -->

      <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all"
          rel="stylesheet"
          type="text/css"/>

          
      <link rel="stylesheet" href="/css/app.css" />
      
      <link rel="stylesheet"
            href="{{ url('css') }}/inventory.css"/>
      <link rel="stylesheet" href="/css/font.css" />
            
      <link rel="stylesheet" href="/dist/plugins/font-awesome/css/font-awesome.min.css">
      <!-- Ionicons -->
      <!-- {{-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}} -->
      <!-- Theme style -->


      <link rel="stylesheet" href="/dist/css/adminlte.min.css">
      <!-- {{-- <link rel="stylesheet" href="/dist/css/adminlte-skin-midnight.min.css"> --}} -->

      <link rel="stylesheet" href="/dist/plugins/bootstrap/css/bootstrap.min.css">
      <!-- {{-- <link rel="stylesheet" href="/dist/plugins/mdb/css/mdb.min.css"> --}} -->


      <link rel="stylesheet" href="/dist/plugins/materialize/css/materialize.min.css">


      
      <!-- iCheck -->
      <!-- {{-- <link rel="stylesheet" href="/dist/plugins/iCheck/flat/blue.css"> --}} -->
      <!-- Morris chart -->
      <!-- {{-- <link rel="stylesheet" href="/dist/plugins/morris/morris.css"> --}} -->
      <!-- jvectormap -->
      <!-- {{-- <link rel="stylesheet" href="/dist/plugins/jvectormap/jquery-jvectormap-1.2.2.css"> --}} -->
      <!-- Date Picker -->
      <link rel="stylesheet" href="/dist/plugins/datepicker/datepicker3.css">
      
      
      <!-- {{-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"> --}} -->
      <!-- bootstrap wysihtml5 - text editor -->
      <!-- {{-- <link rel="stylesheet" href="/dist/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css"> --}} -->
      <!-- Google Font: Source Sans Pro -->
      <!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
      <!-- IonIcons -->
      <!-- {{-- <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> --}} -->
      
      
      <!-- Datatables -->
      <link rel="stylesheet" href="/dist/plugins/datatables/dataTables.bootstrap4.css">
      
      <!-- Overlay scrollbar -->
      <link rel="stylesheet" href="/dist/plugins/overlayscrollbar/overlayscrollbar.min.css">
      
      <!-- Daterange picker -->
      <link rel="stylesheet" href="/dist/plugins/daterangepicker/daterangepicker.css">
      <!-- Bootstrap Select -->
      <link rel="stylesheet" href="/dist/plugins/bootstrap-select/bootstrap-select.min.css">

      <!-- jQuery Multi Select -->
      <link rel="stylesheet" href="/dist/plugins/multiselect/jquery-multi-select.min.css">

      <!-- MDB -->
      <link rel="stylesheet" href="/dist/plugins/mdb/css/addons/datatables.min.css">
      <link rel="stylesheet" href="/dist/plugins/mdb/css/addons/datatables-select.min.css">


      <!-- TOASTR -->
      <link rel="stylesheet" href="/dist/plugins/toastr/css/toastr.min.css">


      <link rel="stylesheet" href="/dist/plugins/datetimepicker/css/bootstrap-datetimepicker.min.css">


      <!-- Kratik Fileinput-->
      <link rel="stylesheet" href="/dist/plugins/kratik-bootstrap-fileinput/css/fileinput.min.css">

      <link rel="stylesheet" href="{{ url('quickadmin/css') }}/components.css"/>
      <link rel="stylesheet" href="{{ url('quickadmin/css') }}/quickadmin-layout.css"/>
      <link rel="stylesheet" href="{{ url('quickadmin/css') }}/quickadmin-theme-default.css"/>


      <!-- pushed styles -->
      @stack('styles')


      <link rel="stylesheet" href="/css/default.css" />
</head>

<body class="hold-transition sidebar-mini skin-midnight sidebar-open">