{{-- <x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-jet-button class="ml-4">
                    {{ __('Log in') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout> --}}

@extends('layouts.autlayout')

@section('title') Login @endsection

@section('content')

  <!-- /.login-logo -->
  <div class="card" id="login-page-css">
    <div class="login-box">
      <div class="login-logo">
        <img src="../../dist/img/Group 9767.png" class="img-fluid"></img>
      </div>
    <div class="card-body login-card-body">
        <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" placeholder="Email Address">

          <div class="input-group-append">
            <div class="input-group-text">
              {{-- <span class="fas fa-envelope"></span> --}}
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">

              <span class=""><img src="../../dist/img/Eye---Slash.png" class="img-fluid" alt=""></span>
            </div>
          </div>
        </div>
        <div class="text-end">
          <a href="{{ route('password.request') }}">Forget password?</a>
        </div>
        <div class="social-auth-links text-center mb-3">
          <p>- OR -</p>
          <button type="submit" class="btn btn-block  text-white">
             Sign in
          </button>
      </form>


      </div>
      <!-- /.social-auth-links -->
    {{-- <div class="text-center signuplink">
      <p class="mb-2">Do you have account?
        <a href="forgot-password.html">Signup now</a>
      </p>

    </div> --}}

    <!-- /.login-card-body -->
  </div>
  <div class="login-footer text-center">
    <p>&copy;2023 Crafted with &hearts; by Oscorb</p>
  </div>
</div>
<!-- /.login-box -->
{{-- <div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-0">
            <div class="card">
                <div class="card-header">
                    <div class="bg-login text-center">
                        <div class="bg-login-overlay"></div>
                        <div class="position-relative">
                            <h5 class="font-size-20">Welcome Back !</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="username">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" @if(old('email')) value="{{ old('email') }}" @endif required autocomplete="email" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="userpassword">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" value="" name="password" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="remember" id="customControlInline" {{ old('remember') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="customControlInline">{{ __('Remember Me') }}</label>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-primary btn-block waves-effect waves-light" id="login" type="submit">{{ __('Login') }}</button>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="{{ route('password.request') }}" class="text-muted"><i class="mdi mdi-lock mr-1"></i> {{ __('Forgot Your Password?') }}</a>
                        </div>
                    </form>

                    <div class="text-center">
                        <p>Don't have an account ? <a href="register" class="font-weight-medium text-primary"> Signup now </a> </p>
                        <p>© <script>
                                document.write(new Date().getFullYear())
                            </script> Crafted with <i class="mdi mdi-heart text-danger"></i> by Oscorb</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}

@endsection
