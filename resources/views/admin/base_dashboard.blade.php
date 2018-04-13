<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ResApp - Admin</title>
  <link rel="icon" href="{{ asset('img/resapp-logo.png') }}" type="image/gif" sizes="16x16">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  

  {!! HTML::style('css/admin.css') !!}
  <!-- Ajax Loader -->
  {!! HTML::style('css/ajax-loader.css') !!}
  <!-- Bootstrap 3.3.5 -->
  {!! HTML::style('bower_components/admin-lte/bootstrap/css/bootstrap.min.css') !!}
  <!-- Font Awesome -->
  {!! HTML::style('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') !!}
  <!-- Ionicons -->
  {!! HTML::style('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}
  <!-- Theme style -->
  {!! HTML::style('bower_components/admin-lte/dist/css/AdminLTE.css') !!}
  <!-- DataTables -->
  {!! HTML::style('bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.css') !!}

  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <!-- iCheck for checkboxes and radio inputs -->
  {!! HTML::style('bower_components/admin-lte/plugins/iCheck/all.css') !!}
  {!! HTML::style('bower_components/admin-lte/dist/css/skins/skin-purple-light.min.css') !!}
  {!! HTML::style('bower_components/admin-lte/plugins/select2/select2.min.css') !!}
  <!-- Dropzone -->
  {!! HTML::style('dropzone/basic.css') !!}
  {!! HTML::style('dropzone/dropzone.css') !!}
  {!! HTML::style('dropzone/min/basic.min.css') !!}
  {!! HTML::style('dropzone/min/dropzone.min.css') !!}
  <!-- fullCalendar 2.2.5-->
  {!! HTML::style('bower_components/admin-lte/plugins/fullcalendar/fullcalendar.min.css') !!}
  <!-- Time picker -->
  {!! HTML::style('bower_components/admin-lte/plugins/timepicker/bootstrap-timepicker.min.css') !!}
  <!-- Date picker -->
  {!! HTML::style('bower_components/admin-lte/plugins/datepicker/datepicker3.css') !!}
  <!-- daterange picker -->
  {!! HTML::style('bower_components/admin-lte/plugins/daterangepicker/daterangepicker-bs3.css') !!}


  <!-- REQUIRED JS SCRIPTS -->
  <!-- Dropzone -->
  {!! HTML::script('dropzone/dropzone.js'); !!}
  <!-- jQuery 2.1.4 -->
  {!! HTML::script('bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js') !!}
  <!-- Bootstrap 3.3.5 -->
  {!! HTML::script('bower_components/admin-lte/bootstrap/js/bootstrap.min.js') !!}
  <!-- AdminLTE App -->
  {!! HTML::script('bower_components/admin-lte/dist/js/app.min.js') !!}
  {!! HTML::script('bower_components/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js') !!}
  {!! HTML::script('bower_components/admin-lte/plugins/fastclick/fastclick.js') !!}
  <!-- PLUGINS  -->
  {!! HTML::script('bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js') !!}
  {!! HTML::script('bower_components/admin-lte/plugins/input-mask/jquery.inputmask.js') !!}
  {!! HTML::script('bower_components/admin-lte/plugins/input-mask/jquery.inputmask.date.extensions.js') !!}
  {!! HTML::script('bower_components/admin-lte/plugins/input-mask/jquery.inputmask.extensions.js') !!}
  <!-- DataTables -->
  {!! HTML::script('bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js') !!}
  {!! HTML::script('bower_components/admin-lte/plugins/datatables/dataTables.bootstrap.min.js') !!}

  <!-- iCheck-->
  {!! HTML::script('bower_components/admin-lte/plugins/iCheck/icheck.min.js') !!}
  <!-- fullCalendar 2.2.5 -->
  {!! HTML::script('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js') !!}
  {!! HTML::script('bower_components/admin-lte/plugins/fullcalendar/fullcalendar.min.js') !!}
  <!-- Timepicker -->
  {!! HTML::script('bower_components/admin-lte/plugins/timepicker/bootstrap-timepicker.min.js') !!}
  <!-- bootstrap datepicker -->
  {!! HTML::script('bower_components/admin-lte/plugins/datepicker/bootstrap-datepicker.js') !!}
  <!-- date-range-picker -->
  {!! HTML::script('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js') !!}
  {!! HTML::script('bower_components/admin-lte/plugins/daterangepicker/daterangepicker.js') !!}

  <script>
    $(function(){
      $("[data-mask]").inputmask();
    });
    $(function () {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      });
    });
  </script>
</head>
<body class="hold-transition skin-purple-light sidebar-mini">
  <div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

      <!-- Logo -->
      <a href="index2.html" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>RES</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>RES</b>app</span>
      </a>

      <!-- Header Navbar -->
      <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
              <!-- Menu Toggle Button -->
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <!-- The user image in the navbar-->
                <img src="{{ asset(Auth::user()->profile_picture_path) }}" class="user-image" alt="User Image">
                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                <span class="hidden-xs">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
              </a>
              <ul class="dropdown-menu">
                <!-- The user image in the menu -->
                <li class="user-header">
                  <img src="<?php echo asset("").Auth::user()->profile_picture_path?>" class="img-circle" alt="User Image">
                  <p>
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    <small>Member since <?php echo Auth::user()->created_at ?></small>
                  </p>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-right">
                    <a href="{{ URL::route('admin_logout') }}" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Control Sidebar Toggle Button -->
            
          </ul>
        </div>
      </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="<?php echo asset("").Auth::user()->profile_picture_path?>" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ Auth::user()->email }}</p>
            <!-- Status -->
            <i class="fa fa-circle text-success"></i> Online
          </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
          <li class="header">HEADER</li>
          <!-- Optionally, you can add icons to the links -->
          <li>
              <a href="{{ URL::route('admin_dashboard') }}"><i class="fa fa-television"></i> <span>Dashboard</span></a>
          </li>
          <li class="treeview">
            <a href="#"><i class="fa fa-building-o"></i> <span>Developers</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::route('admin_create_developer') }}"><i class="fa fa-plus"></i>Add Developer</a></li>
              <li><a href="{{ URL::route('admin_all_developers') }}"><i class="fa fa-list-ol"></i>All Developers</a></li>
            </ul>
          </li>
          <li class="treeview">
            <a href="#"><i class="fa fa-users"></i> <span>Brokers</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::route('admin_non_agents') }}"><i class="fa fa-plus"></i>Add Broker</a></li>
              <li><a href="{{ URL::route('admin_all_accounts_agent') }}"><i class="fa fa-list-ol"></i>All Brokers</a></li>
            </ul>
          </li>
          <!-- <li class="treeview">
              <a href="#"><i class="fa fa-bar-chart-o"></i> <span>Reports</span></a>
          </li> -->
          <li class="treeview">
            <a href="#"><i class="fa fa-user"></i> <span>Accounts</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::route('admin_create_account_admin') }}"><i class="fa fa-user-plus"></i>Add Account</a></li>
              <li><a href="{{  URL::route('admin_all_accounts_admin') }}"><i class="fa fa-list-ol"></i>All Accounts</a></li>
            </ul>
          </li>
        </ul>
        <!-- /.sidebar-menu -->
      </section>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      @yield('content')
    </div>
    <!-- /.content-wrapper -->

    @extends('includes.footer')

</body>
</html>
