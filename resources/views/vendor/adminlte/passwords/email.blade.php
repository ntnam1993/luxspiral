@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
    <div class="login-box">
        <div class="login-logo">
            {{--<a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>--}}
            <a class="ttauth" href=""><img src="{{asset('/admin/img/lux.png')}}" alt="LUX" height="100" width="250" />
            </a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            @if (session('status'))
                <div class="send-msg-success">
                    {{ session('status') }}
                </div>
                <div>
                    <a href="{{ route('login') }}" class="btn btn-primary btn-flat btn-goto-login">ログイン画面へ</a>
                </div>
            @else
                <p class="login-box-msg">パスワードを再発行いたします。</p>
                <p class="login-box-msgsend">ご登録されているメールアドレスを入力してください。</p>
                <form action="{{ url(config('adminlte.password_email_url', 'password/email')) }}" method="post">
                    {!! csrf_field() !!}

                    <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                        <input type="email" name="email" class="form-control" value="{{ $email or old('email') }}"
                               placeholder="{{ trans('auth.email') }}">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        @if ($errors->has('email'))
                            <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <button type="submit"
                            class="btn btn-primary btn-block btn-flat"
                    >{{ trans('auth.send_password_reset_link') }}</button>
                </form>
            @endif
        </div>
        <!-- /.login-box-body -->
    </div><!-- /.login-box -->
@stop

@section('adminlte_js')
    @yield('js')
@stop
