@extends('frontend.layout')

@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->blog_page_title ?? __('Blog') }}
  @else
    {{ __('Blog') }}
  @endif
@endsection


@php
  $metaKeywords = !empty($seo->meta_keyword_blog) ? $seo->meta_keyword_blog : '';
  $metaDescription = !empty($seo->meta_description_blog) ? $seo->meta_description_blog : '';
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
            {{ $pageHeading->blog_page_title ?? __('Blog') }}
          @else
            {{ __('Blog') }}
          @endif
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">
              @if (!empty($pageHeading))
                {{ $pageHeading->blog_page_title ?? __('Blog') }}
              @else
                {{ __('Blog') }}
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


  <!--====== BLOG STANDARD PART START ======-->
  <section class="blog-page-section py-120 rpy-100">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="blog-standard">
            <div class="row">
              @if (count($blogs) == 0)
                <div class="col">
                  <h3 class="mt-40 text-center">{{ __('No Blog Found') . '!' }}</h3>
                </div>
              @else
                @foreach ($blogs as $blog)
                  <div class="col-md-6">
                    <div class="blog-item">
                      <div class="blog-image">
                        <a href="{{ route('blog_details', ['slug' => $blog->slug]) }}">
                          <img data-src="{{ asset('assets/admin/img/blogs/' . $blog->image) }}" class="lazy" alt="image">
                        </a>
                      </div>
                      <div class="blog-content">
                        <a class="category"
                          href="{{ route('blogs', ['category' => $blog->categorySlug]) }}">{{ $blog->categoryName }}</a>
                        <a class="d-block" href="{{ route('blog_details', ['slug' => $blog->slug]) }}">
                          <h4 class="title">
                            {{ strlen($blog->title) > 30 ? mb_substr($blog->title, 0, 30, 'UTF-8') . '...' : $blog->title }}
                          </h4>
                        </a>
                        <p>{!! strlen(strip_tags($blog->content)) > 100
                            ? mb_substr(strip_tags($blog->content), 0, 100, 'UTF-8') . '...'
                            : strip_tags($blog->content) !!}</p>
                        <ul class="blog-footer">
                          <li><i class="fas fa-calendar-alt"></i> {{ date_format($blog->created_at, 'M d, Y') }}</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>

            @if (count($blogs) > 0)
              {{ $blogs->appends([
                      'title' => request()->input('title'),
                      'category' => request()->input('category'),
                  ])->links() }}
            @endif

          </div>

          @if (!empty(showAd(3)))
            <div class="text-center mt-30">
              {!! showAd(3) !!}
            </div>
          @endif
        </div>

        @includeIf('frontend.journal.side-bar')
      </div>
    </div>
  </section>
  <!--====== BLOG STANDARD PART END ======-->
@endsection

@section('script')
  <script type="text/javascript" src="{{ asset('assets/admin/js/blog.js') }}"></script>
@endsection
