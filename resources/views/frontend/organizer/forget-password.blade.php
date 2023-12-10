@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->organizer_forget_password_page_title ?? __('Forget Password') }}
  @else
    {{ __('Forget Password') }}
  @endif
@endsection
@php
  $metaKeywords = !empty($seo->meta_keyword_organizer_forget_password) ? $seo->meta_keyword_organizer_forget_password : '';
  $metaDescription = !empty($seo->meta_description_organizer_forget_password) ? $seo->meta_description_organizer_forget_password : '';
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
            {{ $pageHeading->organizer_forget_password_page_title ?? __('Forget Password') }}
          @else
            {{ __('Forget Password') }}
          @endif
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">
              @if (!empty($pageHeading))
                {{ $pageHeading->organizer_forget_password_page_title ?? __('Forget Password') }}
              @else
                {{ __('Forget Password') }}
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

          @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
          @endif
          @if (Session::has('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
          @endif
          <form id="login-form" name="login_form" class="login-form" action="{{ route('organizer.forget.mail') }}"
            method="POST">
            @csrf
            <div class="form-group">
              <label for="email">{{ __('Email Address') }} *</label>
              <input type="email" name="email" value="{{ old('email') }}" id="email" class="form-control"
                placeholder="{{ __('Enter Your Email') }}" required>
              @error('email')
                <p class="text-danger">{{ $message }}</p>
              @enderror
            </div>
            <div class="form-group mb-0">
              <button class="theme-btn br-30" type="submit">{{ __('PROCEED') }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- LogIn Area End -->
@endsection
