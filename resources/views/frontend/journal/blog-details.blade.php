@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->blog_details_page_title ?? __('Blog Details') }}
  @else
    {{ __('Blog Details') }}
  @endif
@endsection

@section('metaKeywords') {{ $details->meta_keywords }} @endsection

@section('metaDescription') {{ $details->meta_description }} @endsection

@php
  $og_title = $details->title;
  $og_description = strip_tags($details->content);
  $og_image = asset('assets/admin/img/blogs/' . $details->image);
@endphp

@section('og-title', "$og_title")
@section('og-description', "$og_description")
@section('og-image', "$og_image")
@section('custom-style')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-content.css') }}">
@endsection

@section('content')
@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">
          {{ strlen($details->title) > 30 ? mb_substr($details->title, 0, 30, 'UTF-8') . '...' : $details->title }}
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">
              @if (!empty($pageHeading))
                {{ $pageHeading->blog_details_page_title ?? __('Blog Details') }}
              @else
                {{ __('Blog Details') }}
              @endif
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </section>
  <!-- Page Banner End -->
@endsection

<!--====== BLOG DETAILS PART START ======-->
<section class="blog-details py-120 rpy-100">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="blog-details-content">
          <div class="image mb-30">
            <img data-src="{{ asset('assets/admin/img/blogs/' . $details->image) }}" class="lazy" alt="image">
          </div>

          <div class="blog-details-top">
            <ul class="blog-meta mb-5">
              <li>
                <i class="fa fa-calendar-alt"></i> {{ date_format($details->created_at, 'M d, Y') }}
              </li>
              <li>
                <i class="fa fa-tag"></i>
                <span><a
                    href="{{ route('blogs', ['category' => $details->blogSlug]) }}">{{ $details->categoryName }}</a></span>
              </li>
              <li>
                <i class="fa fa-tag"></i>
                <span>{{ $details->author }}</span>
              </li>
            </ul>
            <h3 class="blog-title mb-20">{{ $details->title }}</h3>
            <div class="summernote-content">
              {!! $details->content !!}
            </div>
          </div>

          <div class="tag-share pt-20 pb-50">
            <div class="social-style-two pb-15">
              <b>Share:</b>
              <a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i
                  class="fab fa-facebook-f"></i></a>
              <a href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"><i
                  class="fab fa-twitter"></i></a>
              <a
                href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $details->title }}"><i
                  class="fab fa-linkedin"></i></a>
            </div>
          </div>

          @if (!empty(showAd(3)))
            <div class="text-center">
              {!! showAd(3) !!}
            </div>
          @endif

          <div class="blog-details-releted-post mt-45">
            <h3 class="blog-title mb-10">{{ __('Related Blog') }}</h3>
            <hr>
            @if (count($relatedBlogs) == 0)
              <div class="row text-center">
                <div class="col">
                  <h5 class="mt-40">{{ __('No Related Blog Found') . '!' }}</h5>
                </div>
              </div>
            @else
              <div class="row">

                @foreach ($relatedBlogs as $relatedBlog)
                  <div class="col-md-6">
                    <div class="blog-item">
                      <div class="blog-image">
                        <a href="{{ route('blog_details', ['slug' => $relatedBlog->slug]) }}">
                          <img data-src="{{ asset('assets/admin/img/blogs/' . $relatedBlog->image) }}" class="lazy"
                            alt="image">
                        </a>
                      </div>
                      <div class="blog-content">
                        <a class="category"
                          href="{{ route('blogs', ['category' => $relatedBlog->categorySlug]) }}">{{ $relatedBlog->categoryName }}</a>
                        <a class="d-block" href="{{ route('blog_details', ['slug' => $relatedBlog->slug]) }}">
                          <h4 class="title">
                            {{ strlen($relatedBlog->title) > 30 ? mb_substr($relatedBlog->title, 0, 30, 'UTF-8') . '...' : $relatedBlog->title }}
                          </h4>
                        </a>
                        <p>{!! strlen(strip_tags($relatedBlog->content)) > 100
                            ? mb_substr(strip_tags($relatedBlog->content), 0, 100, 'UTF-8') . '...'
                            : strip_tags($relatedBlog->content) !!}</p>
                        <ul class="blog-footer">
                          <li><i class="fas fa-calendar-alt"></i> {{ date_format($relatedBlog->created_at, 'M d, Y') }}
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            @endif
          </div>

          @if (!empty(showAd(3)))
            <div class="text-center mt-30">
              {!! showAd(3) !!}
            </div>
          @endif

          @if ($disqusInfo->disqus_status == 1)
            <div id="disqus_thread" class="mt-45"></div>
          @endif
        </div>
      </div>

      @includeIf('frontend.journal.side-bar')
    </div>
  </div>
</section>
<!--====== BLOG DETAILS PART END ======-->
@endsection

@section('script')
<script>
  "use strict";
  const shortName = '{{ $disqusInfo->disqus_short_name }}';
</script>

<script type="text/javascript" src="{{ asset('assets/admin/js/blog.js') }}"></script>
@endsection
