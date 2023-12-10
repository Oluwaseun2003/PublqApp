@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->faq_page_title ?? __('F.A.Q') }}
  @else
    {{ __('F.A.Q') }}
  @endif
@endsection


@php
  $metaKeywords = !empty($seo->meta_keyword_faq) ? $seo->meta_keyword_faq : '';
  $metaDescription = !empty($seo->meta_description_faq) ? $seo->meta_description_faq : '';
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
            {{ $pageHeading->faq_page_title ?? __('F.A.Q') }}
          @else
            {{ __('F.A.Q') }}
          @endif
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">
              @if (!empty($pageHeading))
                {{ $pageHeading->faq_page_title ?? __('F.A.Q') }}
              @else
                {{ __('F.A.Q') }}
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


  <!--====== FAQ PART START ======-->
  <section class="faq-area pt-80 pb-80">
    <div class="container">
      <div class="row">
        <div class="col">
          @if (count($faqs) == 0)
            <h3 class="text-center">{{ __('No FAQ Found') . '!' }}</h3>
          @else
            <div class="faq-accordion">
              <div class="accordion" id="accordionExample">
                @foreach ($faqs as $faq)
                  <div class="card">
                    <div class="card-header" id="{{ 'heading-' . $faq->id }}">
                      <a class="{{ $loop->first ? '' : 'collapsed' }}" href="" data-toggle="collapse"
                        data-target="{{ '#collapse-' . $faq->id }}"
                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                        aria-controls="{{ 'collapse-' . $faq->id }}">
                        {{ $faq->question }}
                      </a>
                    </div>

                    <div id="{{ 'collapse-' . $faq->id }}" class="collapse {{ $loop->first ? 'show' : '' }}"
                      aria-labelledby="{{ 'heading-' . $faq->id }}" data-parent="#accordionExample">
                      <div class="card-body">
                        <p>{{ $faq->answer }}</p>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>
      </div>

      @if (!empty(showAd(3)))
        <div class="text-center mt-30">
          {!! showAd(3) !!}
        </div>
      @endif
    </div>
  </section>
  <!--====== FAQ PART END ======-->
@endsection
