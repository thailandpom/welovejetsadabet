@extends('layouts.app')

@section('content')
<div id="page-container" class="h-100">
    <div class="h-100">
        <!-- Main Container -->
        <main id="main-container" class="h-100">

            <!-- Page Content -->
            <div class="row no-gutters justify-content-center bg-body-dark h-100">
                <div class="hero-static col-sm-10 col-md-8 col-xl-6 d-flex align-items-center p-2 px-sm-0">
                    <!-- Sign In Block -->
                    <div class="block block-rounded block-transparent block-fx-pop w-100 mb-0 overflow-hidden bg-image" style="background-image: url('{{asset('/assets/images/contact_img.png')}}');">
                        <div class="row justify-content-center">
                            <div class="col-md-6 order-md-1 bg-white">
                                <div class="block-content block-content-full px-lg-5 py-md-5 py-lg-6">
                                    <!-- Header -->
                                    <div class="mb-2 text-center">
                                        <a class="link-fx font-w700 font-size-h1" href="#">
                                            <img class="img-logo"  src="" style="height: 40px;">
                                        </a>
                                        <p class="text-uppercase font-w700 font-size-sm text-muted pt-2">Sign In</p>
                                    </div>
                                    <!-- END Header -->
                                    <form class="js-validation-signin" method="POST" action="{{ route('login') }}">
                                        @csrf

                                        @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                        <div class="form-group">
                                            <input id="email" type="email"
                                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} form-control-alt"
                                                name="email" value="{{ old('email') }}"
                                                placeholder="{{ __('E-Mail Address') }}" required autofocus>

                                        </div>
                                        @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-alt" id="password" name="password" placeholder="{{ __('Password') }}" autocomplete = "off" >
                                        </div>
                                        <div class="form-group">

                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-block btn-hero-primary" style="">
                                                Sign In
                                            </button>
                                        </div>
                                    </form>
                                    <!-- END Sign In Form -->
                                </div>
                            </div>
                        
                        </div>
                    </div>
                    <!-- END Sign In Block -->
                </div>
            </div>
            <!-- END Page Content -->

        </main>
        <!-- END Main Container -->
    </div>
</div>



@endsection
