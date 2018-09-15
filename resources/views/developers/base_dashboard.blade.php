<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Maharlika Land Properties and Homes Corp</title>
  <link rel="icon" href="{{ asset('img/maharlika.png') }}" type="image/gif" sizes="16x16">
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
  {!! HTML::style('bower_components/admin-lte/dist/css/skins/skin-green-light.min.css') !!}
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
<body class="hold-transition skin-green-light sidebar-mini">
  <div class="wrapper">
    <header class="main-header">
      <a href="{{ URL::route('developer_dashboard') }}" class="logo">
        <span class="logo-mini"><b>MLPHC</b></span>
        <span class="logo-lg"><b>Maharlika</b>Land</span>
      </a>
      <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?php echo asset("").Auth::user()->profile_picture_path?>" class="user-image" alt="User Image">
                <span class="hidden-xs">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
              </a>
              <ul class="dropdown-menu">
                <li class="user-header">
                  <img src="<?php echo asset("").Auth::user()->profile_picture_path?>" class="img-circle" alt="User Image">
                  <p>
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                    <small>Member since <?php echo Auth::user()->created_at ?></small>
                  </p>
                </li>
                <li class="user-footer">
                  <div class="pull-right">
                    <a href="{{ URL::route('developer_logout') }}" class="btn btn-default btn-flat">Sign out</a>
                  </div>
                </li>
              </ul>
            </li>
            </ul>
        </div>
      </nav>
    </header>
    <aside class="main-sidebar">
      <section class="sidebar">
        <div class="user-panel"  style="padding-bottom:25px;" >
          <div class="pull-left image">
            <img src="<?php echo asset("").Auth::user()->profile_picture_path?>" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ Auth::user()->username }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <ul class="sidebar-menu">
          <li class="header"></li>
          <li class="">
              <a href="{{ URL::route('developer_dashboard') }}"><i class="fa fa-television"></i> <span>Dashboard</span></a>
          </li>
          @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
            <li class="treeview {{ request()->is('manage/projects*') ? 'active' : '' }}">
              <a href="#"><i class="fa fa-building-o"></i> <span>Projects</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('add_project') }}"><i class="fa fa-plus"></i>Add Project</a></li>
                <li><a href="{{ URL::route('projects') }}"><i class="fa fa-building-o"></i>All Projects</a></li>
              </ul>
            </li>
          @else
            <li><a href="{{ URL::route('projects') }}"><i class="fa fa-building-o"></i>Projects</a></li>
          @endif
          <li class="treeview  {{ request()->is('manage/ledgers*') ? 'active' : '' }}">
            @if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN') 
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
              <a href="#"><i class="fa fa-pencil-square-o"></i> <span>Ledger Accounts</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('new_ledger_buyers') }}"><i class="fa fa-plus"></i>Add Account</a></li>
                  <li><a href="{{ URL::route('ledgers_buyers') }}"><i class="fa fa-list-ol"></i>All Accounts</a></li>
                  <li><a href="{{ URL::route('penalty_calculator') }}"><i class="fa fa-calculator"></i>Penalty Calculator</a></li>
              </ul>
            @else
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('ledgers_buyers') }}"><i class="fa fa-list-ol"></i>All Accounts</a></li>
                <li><a href="{{ URL::route('penalty_calculator') }}"><i class="fa fa-calculator"></i>Penalty Calculator</a></li>
              </ul>
            @endif
          </li>
          @if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN') 
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN') 
          or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
            <li class="treeview">
              <a href="#"><i class="fa fa-lightbulb-o"></i> <span>Billings</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('bills_electricity_projects') }}"><i class="fa fa-flash"></i>Electricity</a></li>
                <li><a href="{{ URL::route('bills_water_projects') }}"><i class="fa fa-tint"></i>Water</a></li>
              </ul>
            </li>
          @endif
          <li class="treeview  {{ request()->is('manage/accounting*') ? 'active' : '' }}">
            @if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN')
            or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')
            or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
              <a href="#"><i class="fa fa-money"></i> <span>Accounting</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
                  <li><a href="{{ URL::route('account_titles') }}"><i class="fa fa-info-circle"></i>Account Titles</a></li>
                @endif
                <li><a href="{{ URL::route('vouchers') }}"><i class="fa fa-sticky-note-o"></i>Vouchers</a></li>
              </ul>
            @else
              <a href="{{ URL::route('vouchers') }}"><i class="fa fa-sticky-note-o"></i> <span>Vouchers</span></a>
            @endif
          </li>
          @if(Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')
          or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
            <li class="treeview {{ request()->is('manage/buyers*') ? 'active' : '' }}">
              <a href="#"><i class="fa fa-user"></i> <span>Buyers</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('add_buyer') }}"><i class="fa fa-user-plus"></i>New Buyer</a></li>
                <li><a href="{{ URL::route('buyers') }}"><i class="fa fa-users"></i>All Buyers</a></li>
              </ul>
            </li>
          @endif
          <li class="treeview  {{ request()->is('manage/prospect_buyers*') ? 'active' : '' }}">
            <a href="#"><i class="fa fa-user"></i> <span>Prospect Buyers</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::route('add_prospect_buyer') }}"><i class="fa fa-user-plus"></i>New Prospect Buyer</a></li>
              <li><a href="{{ URL::route('prospect_buyers') }}"><i class="fa fa-users"></i>All Prospect Buyers</a></li>
            </ul>
          </li>
          <li class="treeview  {{ request()->is('manage/marketing*') ? 'active' : '' }}">
            <a href="#"><i class="fa fa-bullhorn"></i> <span>Marketing</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::route('promotional_images') }}"><i class="fa fa-camera-retro"></i>Promotional Images</a></li>
              <li><a href="{{ URL::route('promotional_videos') }}"><i class="fa fa-video-camera"></i>Promotional Videos</a></li>
            </ul>
          </li>
           <!-- @if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN') 
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
            <li class="treeview  {{ request()->is('manage/payroll*') ? 'active' : '' }}">
              <a href="#"><i class="fa fa-money"></i> <span>Payroll</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('generate_payroll') }}"><i class="fa fa-print"></i>Generate</a></li>
                <li><a href="{{ URL::route('salary_rates') }}"><i class="fa fa-user"></i>Salary Rates</a></li>
                <li><a href="{{ URL::route('cash_advances') }}"><i class="fa fa-hourglass-start"></i>Cash Advances</a></li>
                <li><a href="{{ URL::route('payroll_deductions') }}"><i class="fa fa-minus"></i>Deductions</a></li>
                <li><a href="{{ URL::route('payroll_additions') }}"><i class="fa fa-plus"></i>Additions</a></li>
                <li><a href="{{ URL::route('holidays') }}"><i class="fa fa-flag"></i>Holidays</a></li>
              </ul>
            </li>
          @endif
         <li class="treeview">
            @if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN') 
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN')
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_SECRETARY'))
              <a href="#"><i class="fa fa-user"></i> <span>Agents</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('add_agent') }}"><i class="fa fa-user-plus"></i>New Agent</a></li>
                <li><a href="{{ URL::route('developers_agents') }}"><i class="fa fa-users"></i>My Agents</a></li>
              </ul>
            @else
              <a href="{{ URL::route('developers_agents') }}"><i class="fa fa-users"></i> <span>My Agents</span></i></a>
            @endif
          </li> 
          <li class=" {{ request()->is('manage/attendance*') ? 'active' : '' }}">
              <a href="{{ URL::route('attendance') }}"><i class="fa fa-calendar"></i> <span>Attendances</span></a>
          </li>
          -->
          <li class="treeview  {{ request()->is('manage/journals*') ? 'active' : '' }}">
            <a href="#"><i class="fa fa-newspaper-o"></i> <span>Journals</span> <i class="fa fa-angle-left pull-right"></i></a>
            <ul class="treeview-menu">
              <li><a href="{{ URL::route('journal_types') }}"><i class="fa fa-info-circle"></i>Types</a></li>
              <li><a href="{{ URL::route('journals') }}"><i class="fa fa-sticky-note-o"></i>Entries</a></li>
            </ul>
          </li>
          @if(Auth::user()->user_type_id == config('constants.USER_TYPE_ADMIN') 
              or Auth::user()->user_type_id == config('constants.USER_TYPE_DEVELOPER_ADMIN'))
            <li class="treeview {{ request()->is('manage/users*') ? 'active' : '' }}">
              <a href="#"><i class="fa fa-users"></i> <span>Users</span> <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="{{ URL::route('my_admin_account',array(Auth::user()->username)) }}"><i class="fa fa-user"></i>My Account</a></li>
                <li><a href="{{ URL::route('add_user') }}"><i class="fa fa-user-plus"></i>New User</a></li>
                <li><a href="{{ URL::route('users') }}"><i class="fa fa-users"></i>All Users</a></li>
              </ul>
            </li>
          @else
            <li><a href="{{ URL::route('my_account',array(Auth::user()->id)) }}"><i class="fa fa-user"></i>My Account</a></li>
          @endif
        </ul>
      </section>
    </aside>
    @include('includes.animations.ajax_loader')
    <div class="content-wrapper">
      @yield('content')
    </div>
    @extends('includes.footer')
  </div>
</body>
</html>
