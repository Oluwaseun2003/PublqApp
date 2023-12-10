@extends('frontend.layout')

@section('pageHeading')
  {{ $pageInfo->title }}
@endsection

@section('metaKeywords')
  {{ $pageInfo->meta_keywords }}
@endsection

@section('metaDescription')
  {{ $pageInfo->meta_description }}
@endsection

@section('og-title', "$pageInfo->title")

@section('custom-style')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-content.css') }}">
@endsection

@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">{{ $pageInfo->title }}</h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ $pageInfo->title }}</li>
          </ol>
        </nav>
      </div>
    </div>
  </section>
  <!-- Page Banner End -->
@endsection

@section('content')

  <!--====== PAGE CONTENT PART START ======-->
  <section class="custom-page-area pt-100 pb-90">
    <div class="container">
      <div class="row">
        <div class="col">
          <div class="summernote-content">
            {!! $pageInfo->content !!}
          </div>
        </div>
      </div>

      @if (!empty(showAd(3)))
        <div class="text-center mt-30">
          {!! showAd(3) !!}
        </div>
      @endif
    </div>
  </section>
  <!--====== PAGE CONTENT PART END ======-->
@endsection
