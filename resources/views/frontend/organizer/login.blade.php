@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->organizer_login_page_title ?? __('Login') }}
  @else
    {{ __('Login') }}
  @endif
@endsection
@php
  $metaKeywords = !empty($seo->meta_keyword_organizer_login) ? $seo->meta_keyword_organizer_login : '';
  $metaDescription = !empty($seo->meta_description_organizer_login) ? $seo->meta_description_organizer_login : '';
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
            {{ $pageHeading->organizer_login_page_title ?? __('Login') }}
          @else
            {{ __('Login') }}
          @endif
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">
              @if (!empty($pageHeading))
                {{ $pageHeading->organizer_login_page_title ?? __('Login') }}
              @else
                {{ __('Login') }}
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

          @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
          @endif
          @if (Session::has('alert'))
            <div class="alert alert-danger">{{ Session::get('alert') }}</div>
          @endif
          <form id="login-form" name="login_form" class="login-form" action="{{ route('organizer.authentication') }}"
            method="POST">
            @csrf
            <div class="form-group">
              <label for="username">{{ __('Username') }} *</label>
              <input type="text" name="username" value="" id="username" class="form-control"
                placeholder="{{ __('Enter Username') }}">
              @error('username')
                <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
            <div class="form-group">
              <label for="password">{{ __('Password') }} *</label>
              <input type="password" name="password" id="password" value="" class="form-control"
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
              <p>{{ __('Don`t have an account') }} ? <a class="text-info"
                  href="{{ route('organizer.signup') }}">{{ __('Signup Now') }}</a></p>
              <p><a href="{{ route('organizer.forget.password') }}">{{ __('Lost your password') }} ?</a></p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- LogIn Area End -->
@endsection
