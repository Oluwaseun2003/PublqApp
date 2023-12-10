@extends('frontend.layout')

@section('pageHeading')
  {{ __('Payment Success') }}
@endsection

@section('style')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-content.css') }}">
@endsection

@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">{{ __('Payment Success') }}</h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Payment Success') }}</li>
          </ol>
        </nav>
      </div>
    </div>
  </section>
  <!-- Page Banner End -->
@endsection

@section('content')
  <!-- Contact Section Start -->
  <div class="booking-message">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="booking-success">
            <div class="icon text-success"><i class="far fa-check-circle"></i></div>
            <h2>{{ __('Success') }}!</h2>

            <p>{{ __('Your transaction was successful') }}.</p>
            <p>{{ __('We have sent you a mail with an invoice') }}.</p>

            <p class="mt-4">{{ __('Thank you') }}.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Contact Section End -->
@endsection
