@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->shop_page_title ?? __('Shop') }}
  @else
    {{ __('Shop') }}
  @endif
@endsection
@php
  $metaKeywords = !empty($seo->meta_keyword_shop) ? $seo->meta_keyword_shop : '';
  $metaDescription = !empty($seo->meta_description_shop) ? $seo->meta_description_shop : '';
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
            {{ $pageHeading->shop_page_title ?? __('Shop') }}
          @else
            {{ __('Shop') }}
          @endif
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">
              @if (!empty($pageHeading))
                {{ $pageHeading->shop_page_title ?? __('Shop') }}
              @else
                {{ __('Shop') }}
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
  <!-- Event Page Start -->
  <section class="event-page-section py-120 rpy-100">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="sidebar rmb-75">
            <div class="widget widget-search">
              <form action="">
                <input type="text" name="search"
                  value="{{ !empty(request()->input('search')) ? request()->input('search') : '' }}"
                  placeholder="{{ __('Search') }}.....">
                <button type="submit" id="product-search-button" class="fa fa-search"></button>
              </form>
            </div>
            <div class="widget widget-cagegory">
              <h5 class="widget-title">{{ __('Category') }}</h5>
              <form action="{{ route('shop') }}" id="catForm">
                <select id="category" name="category" class="widget-select">
                  <option disabled>{{ __('Select a Category') }}</option>
                  <option value="">{{ __('All') }}</option>
                  @foreach ($product_categories as $item)
                    <option {{ request()->input('category') == $item->slug ? 'selected' : '' }}
                      value="{{ $item->slug }}">{{ $item->name }}</option>
                  @endforeach

                </select>
              </form>
            </div>

            <div class="widget price-filter-widget">
              <h5 class="widget-title">{{ __('Price Filter') }}</h5>
              <div class="price-slider-range" id="range-slider"></div>
              <div class="price-btn">
                <input type="text" dir="ltr" id="price" value="{{ request()->input('min') }}" readonly>
                <button class="theme-btn" id="slider_submit">{{ __('Price Filter') }}</button>
              </div>
            </div>


            @if (!empty(showAd(2)))
              <div class="text-center mt-4">
                {!! showAd(2) !!}
              </div>
            @endif
          </div>
        </div>
        <div class="col-lg-8">
          <div class="shop-page-content">
            <form action="{{ route('shop') }}" id="shortForm">
              @if (!empty(request()->input('category')))
                <input type="hidden" id="category-id" name="category"
                  value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">
              @endif

              @if (!empty(request()->input('min')))
                <input type="hidden" name="min"
                  value="{{ !empty(request()->input('min')) ? request()->input('min') : '' }}">
              @endif

              @if (!empty(request()->input('max')))
                <input type="hidden" name="max"
                  value="{{ !empty(request()->input('max')) ? request()->input('max') : '' }}">
              @endif

              @if (!empty(request()->input('search')))
                <input type="hidden" name="search"
                  value="{{ !empty(request()->input('search')) ? request()->input('search') : '' }}">
              @endif

              <div class="products-dropdown pb-35">
                <select class="product_short" name="product_short" id="products-dropdown-select">
                  <option {{ request()->input('product_short') == 'default' ? 'selected' : '' }} value="default">
                    {{ __('Default Sorting') }}</option>
                  <option {{ request()->input('product_short') == 'new' ? 'selected' : '' }} value="new">
                    {{ __('Sort by Latest') }}</option>
                  <option {{ request()->input('product_short') == 'old' ? 'selected' : '' }} value="old">
                    {{ __('Oldest Product') }}</option>
                  <option {{ request()->input('product_short') == 'hight-to-low' ? 'selected' : '' }}
                    value="hight-to-low">
                    {{ __('High To Low') }}</option>
                  <option {{ request()->input('product_short') == 'low-to-high' ? 'selected' : '' }} value="low-to-high">
                    {{ __('Low To High') }}</option>
                </select>
              </div>
            </form>

            <div class="row">
              @if (count($products) > 0)
                @foreach ($products as $item)
                  <div class="col-md-4 col-sm-6">
                    <div class="shop-item">
                      <div class="image">
                        <img class="lazy"
                          data-src="{{ asset('assets/admin/img/product/feature_image/' . $item->feature_image) }}"
                          alt="Product">
                        <div class="product-icons">
                          <a class="cart-link cart" data-href="{{ route('add.cart', $item->id) }}" data-toggle="tooltip"
                            data-placement="top" title="{{ __('Add to Cart') }}"><i class="fas fa-shopping-cart"></i></a>
                          <a href="{{ route('shop.details', ['slug' => $item->slug, 'id' => $item->id]) }}"
                            class="view"><i class="far fa-eye"></i></a>
                        </div>
                      </div>
                      @php
                        $reviews = App\Models\ShopManagement\ProductReview::where('product_id', $item->id)->get();
                        $avarage_rating = App\Models\ShopManagement\ProductReview::where('product_id', $item->id)->avg('review');
                        $avarage_rating = round($avarage_rating, 2);
                      @endphp
                      <div class="content">
                        @if ($basicInfo->is_shop_rating == 1)
                          <div class="ratting">
                            <div class="d-flex justify-content-between">
                              <div class="rate">
                                <div class="rating" style="width:{{ $avarage_rating * 20 }}%"></div>
                              </div>
                            </div>
                          </div>
                        @endif
                        <h6><a
                            href="{{ route('shop.details', ['slug' => $item->slug, 'id' => $item->id]) }}">{{ $item->title }}</a>
                        </h6>
                        <span class="price"
                          dir="ltr">{{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                          {{ $item->current_price }}
                          {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}
                          @if (!is_null($item->previous_price))
                            <del>{{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                              {{ $item->previous_price }}
                              {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}
                            </del>
                          @endif
                        </span>
                      </div>
                    </div>
                  </div>
                @endforeach
              @else
                <div class="col-lg-12">
                  <h3 class="text-center">{{ __('No Product Found') }}</h3>
                </div>
              @endif
            </div>
            {{ $products->links() }}

            @if (!empty(showAd(3)))
              <div class="text-center mt-4">
                {!! showAd(3) !!}
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Event Page End -->

  <form id="filtersForm" class="d-none" action="{{ route('shop') }}" method="GET">
    <input type="hidden" id="category-id" name="category"
      value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">
    <input type="hidden" id="country-id" name="country"
      value="{{ !empty(request()->input('country')) ? request()->input('country') : '' }}">

    <input type="hidden" id="event" name="event"
      value="{{ !empty(request()->input('event')) ? request()->input('event') : '' }}">

    <input type="hidden" id="min-id" name="min"
      value="{{ !empty(request()->input('min')) ? request()->input('min') : '' }}">

    <input type="hidden" id="max-id" name="max"
      value="{{ !empty(request()->input('max')) ? request()->input('max') : '' }}">

    <input type="hidden" id="keyword-id" name="search"
      value="{{ !empty(request()->input('search')) ? request()->input('search') : '' }}">

    <input type="hidden" id="state-id" name="state"
      value="{{ !empty(request()->input('state')) ? request()->input('state') : '' }}">
    <input type="hidden" id="city-id" name="city"
      value="{{ !empty(request()->input('city')) ? request()->input('city') : '' }}">

    <input type="hidden" id="dates-id" name="dates"
      value="{{ !empty(request()->input('dates')) ? request()->input('dates') : '' }}">

    <button type="submit" id="submitBtn"></button>
  </form>
@endsection

@section('custom-script')
  <script type="text/javascript" src="{{ asset('assets/front/js/moment.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/front/js/daterangepicker.min.js') }}"></script>
  <script>
    let min_price = {!! htmlspecialchars($min) !!};
    let max_price = {!! htmlspecialchars($max) !!};
    let curr_min = {!! !empty(request()->input('min')) ? htmlspecialchars(request()->input('min')) : 5 !!};
    let curr_max = {!! !empty(request()->input('max')) ? htmlspecialchars(request()->input('max')) : 800 !!};
  </script>
  <script src="{{ asset('assets/front/js/product.js') }}"></script>
@endsection
