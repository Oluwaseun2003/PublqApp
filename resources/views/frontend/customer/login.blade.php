@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->customer_login_page_title ?? __('Customer Login') }}
  @else
    {{ __('Customer Login') }}
  @endif
@endsection
@php
  $metaKeywords = !empty($seo->meta_keyword_customer_login) ? $seo->meta_keyword_customer_login : '';
  $metaDescription = !empty($seo->meta_description_customer_login) ? $seo->meta_description_customer_login : '';
@endphp
@section('meta-keywords', "{{ $metaKeywords }}")
@section('meta-description', "$metaDescription")

@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">
          @if (!empty($pageHeading))
            {{ $pageHeading->customer_login_page_title ?? __('Customer Login') }}
          @else
            {{ __('Customer Login') }}
          @endif
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">
              @if (!empty($pageHeading))
                {{ $pageHeading->customer_login_page_title ?? __('Customer Login') }}
              @else
                {{ __('Customer Login') }}
              @endif
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </section>
  <!-- Page Banner End -->
@endsection
@section('content')
  <!-- LogIn Area Start -->
  <div class="login-area pt-115 rpt-95 pb-120 rpb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">

          @php
            $input = request()->input('redirected');
          @endphp
          @if (!onlyDigitalItemsInCart())
            @if ($input == 'checkout')
              <div class="form-group w-100">
                <a href="{{ route('shop.checkout', ['type' => 'guest']) }}"
                  class="btn btn-success d-block">{{ __('Checkout as Guest') }}</a>
              </div>
            @endif
          @endif


          <form id="login-form" name="login_form" class="login-form" action="{{ route('customer.authentication') }}"
            method="POST">
            @csrf

            @if ($basicInfo->facebook_login_status == 1 || $basicInfo->google_login_status == 1)
              <div class="form-group overflow-hidden">
                <div class="row justify-content-between mb-3">
                  @if ($basicInfo->facebook_login_status == 1)
                    <a class="text-center text-white {{ $basicInfo->google_login_status == 1 ? 'w-50' : 'w-100' }} pt-2 py-2 bg-facebook"
                      href="{{ route('auth.facebook') }}" class=""><i class="fab fa-facebook-f"></i>
                      {{ __('Login with Facebook') }}</a>
                  @endif

                  @if ($basicInfo->google_login_status == 1)
                    <a class="text-center text-white {{ $basicInfo->facebook_login_status == 1 ? 'w-50' : 'w-100' }}  pt-2 py-2 bg-google"
                      href="{{ route('auth.google') }}" class=""> <i class="fab fa-google"></i>
                      {{ __('Login with Google') }}</a>
                  @endif
                </div>
              </div>
            @endif

            @if (Session::has('success'))
              <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            @if (Session::has('alert'))
              <div class="alert alert-danger">{{ Session::get('alert') }}</div>
            @endif

            <div class="form-group">
              <label for="username">{{ __('Username') . ' *' }} </label>
              <input type="text" placeholder="{{ __('Enter Your Username') }}" name="username" id="username"
                value="" class="form-control">
              @error('username')
                <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
            <div class="form-group">
              <label for="password">{{ __('Password') . ' *' }}</label>
              <input type="password" name="password" value="" id="password" class="form-control"
                placeholder="{{ __('Enter Password') }}">
              @error('password')
                <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>

            @if ($basicInfo->google_recaptcha_status == 1)
              <div class="form-group">
                {!! NoCaptcha::renderJs() !!}
                {!! NoCaptcha::display() !!}
                @error('g-recaptcha-response')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
            @endif

            <div class="form-group mb-0">
              <button class="theme-btn br-30" type="submit"
                data-loading-text="Please wait...">{{ __('Login') }}</button>
            </div>
            <div class="form-group mt-3 d-flex justify-content-between mb-0">
              <p>{{ __('Don`t have an account') . '?' }} <a class="text-info"
                  href="{{ route('customer.signup') }}">{{ __('Signup Now') }}</a></p>
              <p><a href="{{ route('customer.forget.password') }}">{{ __('Lost your password') . '?' }}</a></p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- LogIn Area End -->
@endsection
