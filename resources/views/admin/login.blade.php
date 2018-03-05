<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Resapp Admin</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    {!! HTML::style('css/login.css') !!}
    <!-- Bootstrap 3.3.5 -->
    {!! HTML::style('bower_components/admin-lte/bootstrap/css/bootstrap.min.css') !!}
    <!-- Font Awesome -->
    {!! HTML::style('https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') !!}
    <!-- Ionicons -->
    {!! HTML::style('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}
    <!-- Theme style -->
    {!! HTML::style('bower_components/admin-lte/dist/css/AdminLTE.min.css') !!}

    <!-- iCheck for checkboxes and radio inputs -->
    {!! HTML::style('bower_components/admin-lte/plugins/iCheck/all.css') !!}
    {!! HTML::style('bower_components/admin-lte/dist/css/skins/skin-purple.min.css') !!}
    {!! HTML::style('bower_components/admin-lte/plugins/select2/select2.min.css') !!}
    
    <!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery 2.1.4 -->
    {!! HTML::script('bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js') !!}
    <!-- Bootstrap 3.3.5 -->
    {!! HTML::script('bower_components/admin-lte/bootstrap/js/bootstrap.min.js') !!}
    <!-- AdminLTE App -->
    {!! HTML::script('bower_components/admin-lte/dist/js/app.min.js') !!}
    {!! HTML::script('bower_components/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js') !!}
    {!! HTML::script('bower_components/admin-lte/plugins/fastclick/fastclick.js') !!}
    <!-- iCheck -->
    {!! HTML::script('bower_components/admin-lte/plugins/iCheck/square/blue.css') !!}
    <!-- INPUT MASKS -->
    {!! HTML::script('bower_components/admin-lte/plugins/input-mask/jquery.inputmask.js') !!}
    {!! HTML::script('bower_components/admin-lte/plugins/input-mask/jquery.inputmask.date.extensions.js') !!}
    {!! HTML::script('bower_components/admin-lte/plugins/input-mask/jquery.inputmask.extensions.js') !!}
    {!! HTML::script('bower_components/admin-lte/plugins/iCheck/icheck.min.js') !!}

  </head>
  <body class="hold-transition">
    @include('modals.danger')
    <div class="login-box">
      <div class="login-logo">
        <b>Resapp</b>Admin
      </div>
      <div class="login-box-body">
        <form method="post">
          <div class="form-group has-feedback">
            {!! Form::text('login', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            {!! Form::password('password', ['placeholder'=>'Password', 'class'=>'form-control']) !!}
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember_me">
                </label>
                &nbsp;&nbsp;Remember Me
              </div>
            </div>
            <div class="col-xs-4">
              <button type="submit" class="btn btn-success btn-block btn-flat">Sign In</button>
            </div>
          </div>
        </form>
        <a href="#">I forgot my password</a><br>
      </div>
    </div>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>
