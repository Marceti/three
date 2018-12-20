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

    <title>AdminLTE 3 | Starter</title>


    <link rel="stylesheet" href="{{asset('css/app.css')}}"
    </link>


    <!-- Font Awesome Icons -->
    <!--  <link rel="stylesheet" href="plugins/font-awesome/css/font-awesome.min.css">
     <!-- Theme style -->
    <!-- <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <!--  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">-->
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">


    @include('adminLTE.partials._navbar')

    @include('adminLTE.partials._sidebar')

    @yield('body')

    @include('adminLTE.partials._footer')


</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<script src="{{asset('js/app.js')}}"></script>

<!-- jQuery -->
<!-- <script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<!-- <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<!-- <script src="dist/js/adminlte.min.js"></script> -->
</body>
</html>















