@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->about_page_title ?? __('About Us') }}
  @else
    {{ __('About Us') }}
  @endif
@endsection
@php
  $metaKeywords = !empty($seo->meta_keyword_about) ? $seo->meta_keyword_about : '';
  $metaDescription = !empty($seo->meta_description_about) ? $seo->meta_description_about : '';
@endphp

@section('meta-keywords', "{{ $metaKeywords }}")
@section('meta-description', "$metaDescription")

@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">{{ $pageHeading ? $pageHeading->about_page_title : '' }}</h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ $pageHeading ? $pageHeading->about_page_title : __('About Us') }}</li>
          </ol>
        </nav>
      </div>
    </div>
  </section>
  <!-- Page Banner End -->
@endsection
@section('content')
  <!-- About Section Start -->
  @if ($secInfo->about_section_status == 1)
    <section class="about-section pt-120 pb-120 rpb-95">
      <div class="container">
        @if (is_null($aboutUsSection))
          <h2 class="text-center">{{ __('No data found for about section') }}</h2>
        @endif
        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="about-image-part pt-10 rmb-55">
              @if (!is_null($aboutUsSection))
                <img class="lazy" data-src="{{ asset('assets/admin/img/about-us-section/' . $aboutUsSection->image) }}"
                  alt="About">
              @endif
            </div>
          </div>
          <div class="col-lg-6">
            <div class="about-content">
              <div class="section-title mb-30">
                <h2>{{ $aboutUsSection ? $aboutUsSection->title : '' }}</h2>
              </div>
              <p>{{ $aboutUsSection ? $aboutUsSection->subtitle : '' }}</p>
              <div>
                {!! $aboutUsSection ? $aboutUsSection->text : '' !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
  <!-- About Section End -->

  <!-- Feature Section Start -->
  <section class="feature-section pt-110 mb-0 rpt-90 bg-lighter">
    @if ($secInfo->features_section_status == 1)
      <div class="container pb-90 rpb-70">
        <div class="section-title text-center mb-55">
          <h2>{{ $featureEventSection ? $featureEventSection->title : '' }}</h2>
          <p>{{ $featureEventSection ? $featureEventSection->text : '' }}</p>
          @if (count($featureEventItems) < 1)
            <h2>{{ __('No data found for features section') }}</h2>
          @endif
        </div>
        <div class="row justify-content-center">
          @foreach ($featureEventItems as $item)
            <div class="col-xl-4 col-md-6">
              <div class="feature-item">
                <i class="{{ $item->icon }}"></i>
                <div class="feature-content">
                  <h5>{{ $item->title }}</h5>
                  <p>{{ $item->text }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>

      </div>
    @endif
  </section>
  <!-- Feature Section End -->

  <!-- Testimonial Section Start -->
  @if ($secInfo->testimonials_section_status == 1)
    <section class="testimonial-section pt-120 rpt-80">
      <div class="container">
        <div class="row pb-75 rpb-55">
          <div class="col-lg-4">
            <div class="testimonial-content pt-10 rmb-55">
              <div class="section-title mb-30">
                <h2>{{ $testimonialData ? $testimonialData->title : __('What say our client about us') }}</h2>
              </div>
              <p>{{ $testimonialData ? $testimonialData->text : '' }}</p>
              <div class="total-client-reviews mt-40 bg-lighter">
                <div class="review-images mb-30">
                  @if (!is_null($testimonialData))
                    <img class="lazy" data-src="{{ asset('assets/admin/img/testimonial/' . $testimonialData->image) }}"
                      alt="Reviewer">
                  @else
                    <img class="lazy" data-src="{{ asset('assets/admin/img/testimonial/clients.png') }}"
                      alt="Reviewer">
                  @endif
                  <span class="pluse"><i class="fas fa-plus"></i></span>
                </div>
                <h6>{{ $testimonialData ? $testimonialData->review_text : __('0 Clients Reviews') }}</h6>
              </div>
            </div>
          </div>
          <div class="col-lg-8">
            <div class="testimonial-wrap">
              @if (count($testimonials) > 0)
                <div class="row">
                  @foreach ($testimonials as $item)
                    <div class="col-md-6">
                      <div class="testimonial-item">
                        <div class="author">
                          <img class="lazy" data-src="{{ asset('assets/admin/img/clients/' . $item->image) }}"
                            alt="Author">
                          <div class="content">
                            <h5>{{ $item->name }}</h5>
                            <span>{{ $item->occupation }}</span>
                            <div class="ratting">
                              @for ($i = 1; $i <= $item->rating; $i++)
                                <i class="fas fa-star"></i>
                              @endfor
                            </div>
                          </div>
                        </div>
                        <p>{{ $item->comment }}</p>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <h4 class="text-center">{{ __('No Review Found') }}</h4>
              @endif
            </div>
          </div>
        </div>
        <hr>
      </div>

    </section>
  @endif
  <!-- Testimonial Section End -->



  <!-- Client Logo Start -->
  @if ($secInfo->partner_section_status == 1)
    <section class="client-logo-area text-center pt-95 rpt-80 pb-90 rpb-70">
      <div class="container">
        <div class="section-title mb-55">
          <h2>{{ $partnerInfo ? $partnerInfo->title : __('Our Partner') }}</h2>
          <p>{{ $partnerInfo ? $partnerInfo->text : '' }}</p>
        </div>
        <div class="client-logo-wrap">
          @if (count($partners) > 0)
            @foreach ($partners as $item)
              <div class="client-logo-item">
                <a href="{{ $item->url }}" target="_blank"><img class="lazy"
                    data-src="{{ asset('assets/admin/img/partner/' . $item->image) }}" alt="Client Logo"></a>
              </div>
            @endforeach
          @else
            <h5>{{ __('No Partner Found') }}</h5>
          @endif
        </div>
      </div>
    </section>
  @endif
  <!-- Client Logo End -->
@endsection
