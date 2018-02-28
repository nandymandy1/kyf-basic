@extends('auth.app')

@section('content')
  <body class="login-page">
      <div class="login-box">
          <div class="logo">
              <a href="javascript:void(0);">Arvind<b> KYF</b></a>
              <small>Get to know about your factory</small>
          </div>
          <div class="card">
              <div class="body">
                  <form id="sign_in" method="POST" action="{{ route('login') }}">
                    @csrf
                      <div class="msg">Login</div>
                      <div class="input-group">
                          <span class="input-group-addon">
                              <i class="material-icons">person</i>
                          </span>
                          <div class="form-line">
                            <input id="email" type="email" placeholder="Email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                          </div>
                      </div>
                      <div class="input-group">
                          <span class="input-group-addon">
                              <i class="material-icons">lock</i>
                          </span>
                          <div class="form-line">
                            <input id="password" type="password" placeholder="Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-xs-4 pull-right">
                              <button class="btn btn-block bg-red waves-effect" type="submit">SIGN IN</button>
                          </div>
                      </div>
                      <div class="row m-t-15 m-b--20">
                          <div class="col-xs-6">
                              <a href="/register">Register Now!</a>
                          </div>
                          <div class="col-xs-6 align-right">
                            <a href="{{ route('password.request') }}">
                                Forgot Your Password?
                            </a>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
@endsection
@section('scripts')
  <script src="{{ asset('./js/pages/examples/sign-in.js')}}"></script>
@endsection
