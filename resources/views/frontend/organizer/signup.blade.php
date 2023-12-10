@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->organizer_signup_page_title ?? __('Signup') }}
  @else
    {{ __('Signup') }}
  @endif
@endsection
@php
  $metaKeywords = !empty($seo->meta_keyword_organizer_signup) ? $seo->meta_keyword_organizer_signup : '';
  $metaDescription = !empty($seo->meta_description_organizer_signup) ? $seo->meta_description_organizer_signup : '';
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
            {{ $pageHeading->organizer_signup_page_title ?? __('Signup') }}
          @else
            {{ __('Signup') }}
          @endif
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">
              @if (!empty($pageHeading))
                {{ $pageHeading->organizer_signup_page_title ?? __('Signup') }}
              @else
                {{ __('Signup') }}
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
  <!-- SignUp Area Start -->
  <div class="signup-area pt-115 rpt-95 pb-120 rpb-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <form id="login-form" name="login_form" class="login-form" action="{{ route('organizer.create') }}"
            method="POST">
            @csrf
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="fname"> {{ __('Name') }} *</label>
                  <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control"
                    placeholder="{{ __('Enter Your Full Name') }}">
                  @error('name')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="lname">{{ __('Username') }} *</label>
                  <input type="text" name="username" value="{{ old('username') }}" id="username" class="form-control"
                    placeholder="{{ __('Enter Your Username') }}">
                  @error('username')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label for="email">{{ __('Email Address') }} *</label>
                  <input type="email" name="email" value="{{ old('email') }}" id="email" class="form-control"
                    placeholder="{{ __('Enter Your Email Address') }}">
                  @error('email')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="password">{{ __('Password') }} *</label>
                  <input type="password" name="password" id="password" class="form-control"
                    placeholder="{{ __('Enter Password') }}">
                  @error('password')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="re-password">{{ __('Re-enter Password') }} *</label>
                  <input type="password" name="password_confirmation" id="re-password" class="form-control"
                    placeholder="{{ __('Re-enter Password') }}">
                </div>
              </div>
              @if ($basicInfo->google_recaptcha_status == 1)
                <div class="col-sm-6">
                  <div class="form-group">
                    {!! NoCaptcha::renderJs() !!}
                    {!! NoCaptcha::display() !!}
                    @error('g-recaptcha-response')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
              @endif
            </div>
            <div class="form-group mb-0">
              <button class="theme-btn br-30" type="submit"
                data-loading-text="Please wait...">{{ __('Signup') }}</button>
            </div>
            <div class="form-group mt-3 mb-0">
              <p>{{ __('Already have an account') }}? <a class="text-info"
                  href="{{ route('organizer.login') }}">{{ __('Login Now') }}</a></p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- SignUp Area End -->
@endsection
