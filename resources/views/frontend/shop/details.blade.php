@extends('frontend.layout')
@section('pageHeading')
  {{ $product->title }}
@endsection
@section('meta-keywords', "{{ $product->meta_keywords }}")
@section('meta-description', "$product->meta_description")

@php
  $og_title = $product->title;
  $og_description = strip_tags($product->description);
  $og_image = asset('assets/admin/img/product/feature_image/' . $product->feature_image);
@endphp

@section('og-title', "$og_title")
@section('og-description', "$og_description")
@section('og-image', "$og_image")

@section('custom-style')
  <link rel="stylesheet" href="{{ asset('assets/front/css/common-style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-content.css') }}">
@endsection
@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">{{ __('Shop') }}</h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Product Details') }}</li>
          </ol>
        </nav>
      </div>
    </div>
  </section>
  <!-- Page Banner End -->
@endsection
@section('content')

  <!-- Shop Details Start -->

  <section class="shop-details-area pt-120 rpt-100 pb-95 rpb-75">
    <div class="container">
      @php
        $reviews = App\Models\ShopManagement\ProductReview::where('product_id', $product->id)->get();
        $avarage_rating = App\Models\ShopManagement\ProductReview::where('product_id', $product->id)->avg('review');
        $avarage_rating = round($avarage_rating, 2);
      @endphp
      <div class="shop-details-content">
        <div class="row justify-content-between pb-45">
          <div class="col-lg-6">
            <div class="product-gallery">
              @foreach ($galleries as $gallery)
                <a class="product-image-preview"
                  href="{{ asset('assets/admin/img/product/gallery/' . $gallery->image) }}">
                  <img class="lazy" data-src="{{ asset('assets/admin/img/product/gallery/' . $gallery->image) }}"
                    alt="Preview">
                </a>
              @endforeach
            </div>
            <div class="product-thumb mt-30">
              @foreach ($galleries as $gallery)
                <div class="product-thumb-item">
                  <img class="lazy" data-src="{{ asset('assets/admin/img/product/gallery/' . $gallery->image) }}"
                    alt="Thumb">
                </div>
              @endforeach
            </div>
          </div>
          <div class="col-lg-6 pl-lg-5">
            <div class="descriptions rmt-55 mb-50 rmb-35">
              <h3>{{ $product->title }}</h3>
              <div class="rating-review d-flex align-items-center pt-5 mb-15">
                @if ($basicInfo->is_shop_rating == 1)
                  <div class="ratting">
                    <div class="d-flex justify-content-between">
                      <div class="rate">
                        <div class="rating" style="width:{{ $avarage_rating * 20 }}%"></div>
                      </div>
                    </div>
                  </div>
                @endif


                @if ($product->type == 'digital')
                  <span class="in-stock"><i class="fas fa-check"></i>{{ __('Available') }}</span>
                @else
                  @if ($product->stock > 0)
                    <span class="in-stock"><i class="fas fa-check"></i> {{ __('In Stock') }}</span>
                  @else
                    <span class="in-stock bg-danger"><i class="fas fa-times"></i>{{ __('Out of Stock') }}</span>
                  @endif
                @endif

              </div>
              <div class="shop-price mb-15" dir="ltr">
                @if (!is_null($product->previous_price))
                  <del><span class="price">
                      {{ symbolPrice($product->previous_price) }}
                    </span></del>
                @endif

                <b class="current-price">
                  {{ symbolPrice($product->current_price) }}</b>
              </div>
              <p>{{ $product->summary }}</p>
              <div class="add-to-cart pt-15">
                <form action="javascript:void(0)" method="post">
                  @csrf
                  <input type="hidden" name="product_id" value="{{ $product->id }}">
                  <div class="quantity-input">
                    <button class="quantity-down" id="quantityDown">
                      -
                    </button>
                    <input id="quantity" type="number" value="1" name="quantity">
                    <button class="quantity-up" id="quantityUP">
                      +
                    </button>
                  </div>
                  <div class="btns pt-20">
                    <a class="cart-link2 theme-btn" data-href="{{ route('add.cart2', $product->id) }}"
                      data-toggle="tooltip" data-placement="top" title="{{ __('Add to Cart') }}"
                      class="theme-btn cart-link2">{{ __('Add to Cart') }}</a>
                  </div>
                </form>

              </div>
              <div class="social-style-two mt-30 mb-15">
                <a href="//www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"><i
                    class="fab fa-facebook-f"></i></a>
                <a href="//twitter.com/intent/tweet?text=my share text&amp;url={{ urlencode(url()->current()) }}"><i
                    class="fab fa-twitter"></i></a>
                <a
                  href="//www.linkedin.com/shareArticle?mini=true&amp;url={{ urlencode(url()->current()) }}&amp;title={{ $product->title }}"><i
                    class="fab fa-linkedin"></i></a>
              </div>
              <ul class="product-meta">
                <li><b>{{ __('SKU') }}:</b> <span>{{ $product->sku }}</span></li>
                <li><b>{{ __('Category') }}:</b> <a
                    href="{{ route('shop', ['category' => $product->slug]) }}">{{ $product->category }}</a></li>
              </ul>
            </div>

          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-9">

          <ul class="nav product-information-tab mb-30">
            <li><a href="#details" data-toggle="tab" class="active show">{{ __('Description') }}</a></li>
            @if ($basicInfo->is_shop_rating == 1)
              <li><a href="#review" data-toggle="tab" class="">{{ __('Review') }} ({{ count($reviews) }})</a>
              </li>
            @endif
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade active show" id="details">
              <h4>{{ __('Description') }}</h4>
              <div class="summernote-content">
                {!! $product->description !!}
              </div>
            </div>
            @if ($basicInfo->is_shop_rating == 1)
              <div class="tab-pane fade" id="review">
                <div class="shop-review-area">
                  <div class="shop-review-title">
                    <h3 class="title">{{ convertUtf8($product->title) }}</h3>
                  </div>
                  @if (count($reviews) > 0)
                    @foreach ($reviews as $review)
                      <div class="shop-review-user">
                        @php
                          $customer = App\Models\Customer::where('id', $review->user_id)->first();
                        @endphp
                        <img class="lazy"
                          src="{{ $customer->photo != null ? asset('assets/admin/img/customer-profile/' . $customer->photo) : asset('assets/front/images/profile.jpg') }}"
                          alt="user image" width="60">


                        <ul>
                          <div class="rate">
                            <div class="rating" style="width:{{ $review->review * 20 }}%"></div>
                          </div>
                        </ul>
                        <span><span>{{ convertUtf8($customer->fname) }} {{ convertUtf8($customer->lname) }}</span> –
                          {{ date('d-m-Y', strtotime($review->created_at)) }}</span>
                        <p>{{ convertUtf8($review->comment) }}</p>
                      </div>
                    @endforeach
                  @else
                    <div class="bg-light mt-4 text-center py-5">
                      {{ __('NOT RATED YET') }}
                    </div>
                  @endif
                  @if (Auth::guard('customer')->user())
                    @if (App\Models\ShopManagement\OrderItem::where('user_id', Auth::guard('customer')->user()->id)->where('product_id', $product->id)->exists())
                      <div class="shop-review-form">
                        @error('error')
                          <p class="text-danger my-2">{{ Session::get('error') }}</p>
                        @enderror
                        <form class="mt-5" action="{{ route('product.review.submit') }}" method="POST">@csrf
                          <div class="input-box">
                            <span>{{ __('Comment') }}</span>
                            <textarea name="comment" cols="30" rows="10" placeholder="{{ __('Comment') }}"></textarea>
                          </div>
                          <input type="hidden" value="" id="reviewValue" name="review">
                          <input type="hidden" value="{{ $product->id }}" name="product_id">
                          <div class="input-box">
                            <span>{{ __('Rating') . ' *' }}</span>
                            <div class="review-content ">
                              <ul class="review-value review-1">
                                <li><a class="cursor-pointer" data-href="1"><i class="far fa-star"></i></a></li>
                              </ul>
                              <ul class="review-value review-2">
                                <li><a class="cursor-pointer" data-href="2"><i class="far fa-star"></i></a></li>
                                <li><a class="cursor-pointer" data-href="2"><i class="far fa-star"></i></a></li>
                              </ul>
                              <ul class="review-value review-3">
                                <li><a class="cursor-pointer" data-href="3"><i class="far fa-star"></i></a></li>
                                <li><a class="cursor-pointer" data-href="3"><i class="far fa-star"></i></a></li>
                                <li><a class="cursor-pointer" data-href="3"><i class="far fa-star"></i></a></li>
                              </ul>
                              <ul class="review-value review-4">
                                <li><a class="cursor-pointer" data-href="4"><i class="far fa-star"></i></a></li>
                                <li><a class="cursor-pointer" data-href="4"><i class="far fa-star"></i></a></li>
                                <li><a class="cursor-pointer" data-href="4"><i class="far fa-star"></i></a></li>
                                <li><a class="cursor-pointer" data-href="4"><i class="far fa-star"></i></a></li>
                              </ul>
                              <ul class="review-value review-5">
                                <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                                <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                                <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                                <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                                <li><a class="cursor-pointer" data-href="5"><i class="far fa-star"></i></a></li>
                              </ul>
                            </div>
                          </div>
                          <div class="input-btn mt-3">
                            <button type="submit">{{ __('Submit') }}</button>
                          </div>
                        </form>
                      </div>
                    @endif
                  @else
                    <div class="review-login mt-4">
                      <a class="theme-btn d-inline-block mr-2"
                        href="{{ route('customer.login') }}">{{ __('Login') }}</a> {{ __('to leave a rating') }}
                    </div>
                  @endif
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
      @if (!empty(showAd(3)))
        <div class="text-center mt-4">
          {!! showAd(3) !!}
        </div>
      @endif

    </div>
  </section>
  <!-- Shop Details End -->
@endsection
