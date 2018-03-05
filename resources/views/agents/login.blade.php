<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="<?php echo asset("")?>resapp-logo.png" type="image/gif" sizes="16x16">
    <title>ResApp Agent</title>
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
    {!! HTML::style('bower_components/admin-lte/dist/css/skins/skin-purple.css') !!}
    {!! HTML::style('bower_components/admin-lte/plugins/select2/select2.min.css') !!}
    {!! HTML::style('bower_components/admin-lte/plugins/iCheck/square/blue.css') !!}
    
    <!-- REQUIRED JS SCRIPTS -->
    <!-- jQuery 2.1.4 -->
    {!! HTML::script('bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js') !!}
    <!-- Bootstrap 3.3.5 -->
    {!! HTML::script('bower_components/admin-lte/bootstrap/js/bootstrap.min.js') !!}
    <!-- AdminLTE App -->
    {!! HTML::script('bower_components/admin-lte/dist/js/app.min.js') !!}
    {!! HTML::script('bower_components/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js') !!}
    {!! HTML::script('bower_components/admin-lte/plugins/fastclick/fastclick.js') !!}
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
        <b>Resapp</b>Agent
      </div>
      <div class="login-box-body">
        <form method="post">
          <div class="form-group{{ $errors->has('login') ? ' has-error' : '' }}">
            {!! Form::text('login', null, ['class' => 'form-control', 'placeholder' => 'Email/Username']) !!}
            {!! $errors->first('login', '<span class="help-block">:message</span>') !!}
          </div>
          <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            {!! Form::password('password', ['placeholder'=>'Password', 'class'=>'form-control']) !!}
            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
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
         
        <!-- <div class="social-auth-links text-center">
          <p>- OR -</p>
          <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
            Facebook</a>
          <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
            Google+</a>
        </div>
        <a href="#">I forgot my password</a><br>
      </div> -->
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
