@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->contact_page_title ?? __('Contact') }}
  @else
    {{ __('Contact') }}
  @endif
@endsection
@php
  $metaKeywords = !empty($seo->meta_keyword_contact) ? $seo->meta_keyword_contact : '';
  $metaDescription = !empty($seo->meta_description_contact) ? $seo->meta_description_contact : '';
@endphp
@section('meta-keywords', "{{ $metaKeywords }}")
@section('meta-description', "$metaDescription")

@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">{{ $pageHeading ? $pageHeading->contact_page_title : '' }}</h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ $pageHeading ? $pageHeading->contact_page_title : __('Contact') }}</li>
          </ol>
        </nav>
      </div>
    </div>
  </section>
  <!-- Page Banner End -->
@endsection
@section('content')
  <!-- Contact Section Start -->
  <section class="contact-page py-120 rpy-100">
    <div class="container">
      <div class="row justify-content-between">
        <div class="col-lg-4">
          <div class="contact-information rpb-20">
            <div class="contact-info-item">
              <i class="far fa-map"></i>
              <div class="info-content">
                <h5>{{ __('Our Address') }}</h5>
                <span>{{ !empty($info->contact_addresses) ? $info->contact_addresses : '' }}</span>
              </div>
            </div>
            <div class="contact-info-item">
              <i class="far fa-envelope"></i>
              <div class="info-content">
                <h5>{{ __('Our Email') }}</h5>
                <span><a href="#">{{ !empty($info->contact_mails) ? $info->contact_mails : '' }}</a></span>
              </div>
            </div>
            <div class="contact-info-item">
              <i class="fas fa-phone-alt"></i>
              <div class="info-content">
                <h5>{{ __('Our Phone') }}</h5>
                <span><a href="">{{ !empty($info->contact_numbers) ? $info->contact_numbers : '' }}</a></span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="contact-form">
            <h3 class="comment-title mb-15">{{ __('Send A Message') }}</h3>
            @if (Session::has('success'))
              <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif
            @if (Session::has('error'))
              <div class="alert alert-danger">{{ Session::get('error') }}</div>
              @php
                Session::forget('error');
              @endphp
            @endif
            <form id="comment-form" class="comment-form mt-35" name="comment-form"
              action="{{ route('contact.send_mail') }}" method="post">
              @csrf
              <div class="row clearfix justify-content-center">
                <div class="col-sm-6">
                  <div class="form-group">
                    <input type="text" id="full-name" name="name" class="form-control" value=""
                      placeholder="{{ __('Enter Your Full Name') }}">
                    @error('name')
                      <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <input type="email" id="email" name="email" class="form-control" value=""
                      placeholder="{{ __('Enter Your Email') }}">
                    @error('email')
                      <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <input type="text" name="subject" class="form-control" value=""
                      placeholder="{{ __('Enter Email Subject') }}">
                    @error('subject')
                      <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                </div>
                <div class="col-sm-12">
                  <div class="form-group">
                    <textarea name="message" class="form-control" rows="4" placeholder="{{ __('Write Your Message') }}"></textarea>
                    @error('message')
                      <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>

                </div>
                <div class="col-sm-12">
                  @if ($basicInfo->google_recaptcha_status == 1)
                    <div class="form-group">
                      {!! NoCaptcha::renderJs() !!}
                      {!! NoCaptcha::display() !!}
                      @error('g-recaptcha-response')
                        <p class="text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  @endif
                </div>
                <div class="col-sm-12">
                  <div class="form-group mb-0">
                    <button type="submit" class="theme-btn showLoader">{{ __('Send Message') }}</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    @if (!empty(showAd(3)))
      <div class="text-center mt-30">
        {!! showAd(3) !!}
      </div>
    @endif
  </section>
  <!-- Contact Section End -->

  <!-- Map -->

  <div class="contact-page-map">
    <div class="our-location">
      @if (!empty($info->latitude) && !empty($info->longitude))
        <iframe width="100%" height="600" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
          src="//maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q={{ $info->latitude }},%20{{ $info->longitude }}+({{ $websiteInfo->website_title }})&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
      @endif
    </div>
  </div>
  <!-- Map -->
@endsection
