@extends('layouts.pre')

@section('title')
  Log in
@endsection

@section('body')
  <body class="hold-transition login-page" style="background-image:url({{url('images/background.jpg')}});background-size:cover;">
    <div class="login-box">
      <div class="login-logo">
        <div class="well well-lg">
          Central Digital Learning Teknik Kimia
        </div>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Login dengan akun anda</p>
        <form action="{{ url('/login') }}" method="post" role="form">
            {!! csrf_field() !!}
          <div class="form-group has-feedback{{ $errors->has('no_id') ? ' has-error' : '' }}">
            <input type="text" class="form-control" placeholder="NIM" name="no_id" value="{{ old('no_id') }}">
            @if ($errors->has('no_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('no_id') }}</strong>
                </span>
            @endif
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
            <input type="password" class="form-control" name="password" placeholder="Password">
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox" name="remember"> Remember Me
                </label>
              </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

        <a href="{{ url('/password/reset') }}">I forgot my password</a><br>
        <a href="{{ url('/register') }}" class="text-center">Register a new membership</a>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="{{ asset('plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
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
@endsection
