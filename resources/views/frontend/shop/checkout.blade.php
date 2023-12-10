@extends('frontend.layout')
@section('pageHeading')
  {{ __('Check Out') }}
@endsection
@section('custom-style')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-content.css') }}">
@endsection
<meta name="csrf-token" content="{{ csrf_token() }}">
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
            <li class="breadcrumb-item active">{{ __('Checkout') }}</li>
          </ol>
        </nav>
      </div>
    </div>
  </section>
  <!-- Page Banner End -->
@endsection
@section('content')
  <!-- CheckOut Area Start -->
  <section class="checkout-area pt-120 rpt-95 pb-90 rpb-70">
    <div class="container">
      <form class="form" action="{{ route('shop.buy', ['type' => 'guest']) }}" method="POST"
        enctype="multipart/form-data" id="payment-form">
        @csrf
        @if (Auth::guard('customer')->user())
          <div class="row">
            <div class="col-lg-6">
              <h3 class="from-title mb-25">{{ __('Billing Details') }}</h3>
              <hr>
              <div class="row mt-35">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="fname">{{ __('First Name') }} *</label>
                    <input type="text" name="fname" value="{{ Auth::guard('customer')->user()->fname }}"
                      id="fname" class="form-control" placeholder="{{ __('First Name') }}">
                    @error('fname')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="lname">{{ __('Last Name') . ' *' }}</label>
                    <input type="text" name="lname" value="{{ Auth::guard('customer')->user()->lname }}"
                      id="lname" class="form-control" placeholder="{{ __('Last Name') }}">
                    @error('lname')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="email">{{ __('Email') . '*' }}</label>
                    <input type="text" name="email" id="email"
                      value="{{ Auth::guard('customer')->user()->email }}" class="form-control"
                      placeholder="{{ __('Email') }}">
                    @error('email')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="address">{{ __('Phone') . '*' }}</label>
                    <input type="text" name="phone" id="phone" class="form-control"
                      value="{{ Auth::guard('customer')->user()->phone }}" placeholder="{{ __('Phone Number') }}">
                    @error('phone')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="state">{{ __('Country') . '*' }}</label>
                    <input type="text" name="country" value="{{ Auth::guard('customer')->user()->country }}"
                      class="form-control" placeholder="{{ __('Country') }}">
                    @error('country')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="state">{{ __('State') }}</label>
                    <input type="text" name="state" value="{{ Auth::guard('customer')->user()->state }}"
                      class="form-control" placeholder="{{ __('State') }}">
                    @error('state')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="city">{{ __('City') . ' *' }}</label>
                    <input type="text" name="city" value="{{ Auth::guard('customer')->user()->city }}"
                      class="form-control" placeholder="{{ __('City') }}">
                    @error('city')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="company">{{ __('Zip/Post Code') . ' *' }}</label>
                    <input type="text" name="zip_code" value="{{ Auth::guard('customer')->user()->zip_code }}"
                      class="form-control" placeholder="{{ __('Zip/Post Code') }}">
                    @error('zip_code')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-12">
                  <label for="">{{ __('Address') . ' *' }}</label>
                  <textarea name="address" class="form_control" cols="2" rows="3" placeholder="{{ __('Address') }} ">{{ Auth::guard('customer')->user()->address }}</textarea>
                  @error('address')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-sm-6 mb-10">
                  <input type="checkbox" class="" name="sameas_shipping" id="sameasshipping">
                  <label for="sameasshipping">{{ __('Same as the Shipping Address') }}</label>
                </div>
              </div>
            </div>
            <div class="col-lg-6" id="shipping_address">
              <h3 class="from-title mb-25">{{ __('Shipping Address') }}</h3>
              <hr>
              <div class="row mt-35">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="fname">{{ __('First Name') . ' *' }}</label>
                    <input type="text" name="s_fname" value="{{ Auth::guard('customer')->user()->fname }}"
                      id="fname" class="form-control" placeholder="{{ __('First Name') }}">
                    @error('s_fname')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="lname">{{ __('Last Name') . '*' }}</label>
                    <input type="text" name="s_lname" value="{{ Auth::guard('customer')->user()->lname }}"
                      id="lname" class="form-control" placeholder="{{ __('Last Name') }}">
                    @error('s_lname')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="email">{{ __('Email') . '*' }}</label>
                    <input type="text" name="s_email" id="email"
                      value="{{ Auth::guard('customer')->user()->email }}" class="form-control"
                      placeholder="{{ __('Email Address') }}">
                    @error('s_email')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="address">{{ __('Phone') . '*' }}</label>
                    <input type="text" name="s_phone" id="phone" class="form-control"
                      value="{{ Auth::guard('customer')->user()->phone }}" placeholder="{{ __('Phone Number') }}">
                    @error('s_phone')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="state">{{ __('Country') }}</label>
                    <input type="text" name="s_country" value="{{ Auth::guard('customer')->user()->country }}"
                      class="form-control" placeholder="{{ __('Country') }}">
                    @error('s_country')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="state">{{ __('State') }}</label>
                    <input type="text" name="s_state" value="{{ Auth::guard('customer')->user()->state }}"
                      class="form-control" placeholder="{{ __('State') }}">
                    @error('s_state')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="city">{{ __('City') . ' *' }}</label>
                    <input type="text" name="s_city" value="{{ Auth::guard('customer')->user()->city }}"
                      class="form-control" placeholder="{{ __('City') }}">
                    @error('s_city')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="company">{{ __('Zip/Post Code') . ' *' }}</label>
                    <input type="text" name="s_zip_code" value="{{ Auth::guard('customer')->user()->zip_code }}"
                      class="form-control" placeholder="{{ __('Zip/Post Code') }}">
                    @error('s_zip_code')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-12">
                  <label for="">{{ __('Address') . ' *' }}</label>
                  <textarea name="s_address" class="form_control" cols="2" rows="3" placeholder="{{ __('Address') }}">{{ Auth::guard('customer')->user()->address }}</textarea>
                  @error('s_address')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </div>
          </div>
        @else
          <div class="row">
            <div class="col-lg-6">
              <h3 class="from-title mb-25">{{ __('Billing Address') }}</h3>
              <hr>
              <div class="row mt-35">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="fname">{{ __('First Name') . ' *' }}</label>
                    <input type="text" name="fname" value="" id="fname" class="form-control"
                      placeholder="{{ __('First Name') }}">
                    @error('fname')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="lname">{{ __('Last Name') . '*' }}</label>
                    <input type="text" name="lname" value="" id="lname" class="form-control"
                      placeholder="{{ __('Last Name') }}">
                    @error('lname')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="email">{{ __('Email') . '*' }}</label>
                    <input type="text" name="email" id="email" value="" class="form-control"
                      placeholder="{{ __('Email Address') }}">
                    @error('email')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="address">{{ __('Phone') . '*' }}</label>
                    <input type="text" name="phone" id="phone" class="form-control" value=""
                      placeholder="{{ __('Phone Number') }}">
                    @error('phone')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="state">{{ __('Country') }}</label>
                    <input type="text" name="country" value="" class="form-control"
                      placeholder="{{ __('Country') }}">
                    @error('country')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="state">{{ __('State') }}</label>
                    <input type="text" name="state" value="" class="form-control"
                      placeholder="{{ __('State') }}">
                    @error('state')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="city">{{ __('City') . ' *' }}</label>
                    <input type="text" name="city" value="" class="form-control"
                      placeholder="{{ __('City') }}">
                    @error('city')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="company">{{ __('Zip/Post Code') . ' *' }}</label>
                    <input type="text" name="zip_code" value="" class="form-control" placeholder="Zip Code">
                    @error('zip_code')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-12">
                  <label for="">{{ __('Address') . ' *' }}</label>
                  <textarea name="address" class="form_control" cols="2" rows="3" placeholder="{{ __('Address') }}"></textarea>
                  @error('address')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-sm-6 mb-10">
                  <input type="checkbox" class="" name="sameas_shipping" id="sameasshipping">
                  <label for="sameasshipping">{{ __('Same as the Shipping Address') }}</label>
                </div>
              </div>
            </div>
            <div class="col-lg-6" id="shipping_address">
              <h3 class="from-title mb-25">{{ __('Shipping Address') }}</h3>
              <hr>
              <div class="row mt-35">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="fname">{{ __('First Name') . ' *' }}</label>
                    <input type="text" name="s_fname" value="" id="fname" class="form-control"
                      placeholder="{{ __('First Name') }}">
                    @error('s_fname')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="lname">{{ __('Last Name') . '*' }}</label>
                    <input type="text" name="s_lname" value="" id="lname" class="form-control"
                      placeholder="{{ __('Last Name') }}">
                    @error('s_lname')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="email">{{ __('Email') . '*' }}</label>
                    <input type="text" name="s_email" id="email" value="" class="form-control"
                      placeholder="{{ __('Email Address') }}">
                    @error('s_email')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="address">{{ __('Phone') . '*' }}</label>
                    <input type="text" name="s_phone" id="phone" class="form-control" value=""
                      placeholder="{{ __('Phone Number') }}">
                    @error('s_phone')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="state">{{ __('Country') . '*' }}</label>
                    <input type="text" name="s_country" value="" class="form-control"
                      placeholder="{{ __('Country') }}">
                    @error('s_country')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="state">{{ __('State') }}</label>
                    <input type="text" name="s_state" value="" class="form-control"
                      placeholder="{{ __('State') }}">
                    @error('s_state')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="city">{{ __('City') . ' *' }}</label>
                    <input type="text" name="s_city" value="" class="form-control"
                      placeholder="{{ __('City') }}">
                    @error('s_city')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="company">{{ __('Zip/Post Code') . ' *' }}</label>
                    <input type="text" name="s_zip_code" value="" class="form-control"
                      placeholder="{{ __('Zip Code') }}">
                    @error('s_zip_code')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-sm-12">
                  <label for="">{{ __('Address') . ' *' }}</label>
                  <textarea name="s_address" class="form_control" cols="2" rows="3" placeholder="{{ __('Address') }}"></textarea>
                  @error('s_address')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
            </div>
          </div>
        @endif
        <div class="row">
          @if (!onlyDigitalItemsInCart())
            <div class="col-lg-12 ">
              <div class="cart-total-product mb-5">
                @php
                  $shipping_charges = App\Models\ShopManagement\ShippingCharge::get();
                  $shipping_cost = App\Models\ShopManagement\ShippingCharge::first();
                  $shipping_cost = $shipping_cost->charge;
                @endphp
                @if ($shipping_charges != null)
                  <div class="cart-title">
                    <span class="product-title">#</span>
                    <span class="product-title">{{ __('Method') }}</span>
                    <span class="quantity-title">{{ __('Cost') }}</span>
                  </div>
                  <div class="cart-item-wrap pt-15">
                    @foreach ($shipping_charges as $shipping_charge)
                      <div class="alert fade show cart-single-item">
                        <h6 class="product-name"><input type="radio" value="{{ $shipping_charge->id }}"
                            data-id="{{ $shipping_charge->charge }}" {{ $loop->iteration == 1 ? 'checked' : '' }}
                            name="shipping_method"></h6>
                        <p class="product-name"><strong>{{ $shipping_charge->title }}</strong>
                          <br>{{ $shipping_charge->text }}
                        </p>
                        <span class="product-total-price" dir="ltr">
                          {{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                          <span>{{ $shipping_charge->charge }}</span>
                          {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}
                        </span>
                      </div>
                    @endforeach
                  </div>
                @endif
              </div>
            </div>
          @else
            @php
              $shipping_cost = 0;
            @endphp
          @endif
          <div class="col-lg-6">
            <div class="cart-total-product">
              @php
                $cart_items = Session::get('cart');
              @endphp

              @if ($cart_items != null)
                @php
                  $cartTotal = 0;
                  $countitem = 0;
                  if ($cart_items) {
                      foreach ($cart_items as $p) {
                          $cartTotal += $p['price'] * $p['qty'];
                          $countitem += $p['qty'];
                      }
                  }
                @endphp
              @endif
              @if ($cart_items != null)
                <div class="cart-title">
                  <span class="product-title">{{ __('Product') }}</span>
                  <span class="quantity-title">{{ __('Quantity') }}</span>
                  <span class="total-title">{{ __('Total') }}</span>
                </div>
                <div class="cart-item-wrap pt-15">
                  @foreach ($cart_items as $id => $item)
                    @php
                      $product = App\Models\ShopManagement\Product::where('id', $id)->first();
                    @endphp
                    <div class="alert fade show cart-single-item">
                      <h6 class="product-name">
                        {{ \Illuminate\Support\Str::limit($item['name'], 20, $end = '...') }}</h6>

                      <span class="product-price" dir="ltr">{{ $item['qty'] }}</span>
                      <span class="product-total-price" dir="ltr">
                        {{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                        <span>{{ $item['qty'] * $item['price'] }}</span>
                        {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}
                      </span>
                    </div>
                  @endforeach
                </div>
              @endif
            </div>
          </div>
          <div class="col-lg-6">
            <h3 class="from-title mb-25">{{ __('Order Total') }}</h3>
            <div>
              <div id="couponReload">
                <ul class="package-summary mb-25">
                  <li><span class="text">{{ __('Total Quantity') }}</span> <span class="number"
                      dir="ltr">{{ $countitem }}</span></li>
                  <li><span class="text">{{ __('sub total') }}</span> <span class="number"
                      dir="ltr">{{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                      <span class="cart_total">{{ $cartTotal }}</span>
                      {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}</span>
                  </li>
                  @if (Session::get('Shop_discount') != '')
                    <li><span class="text">{{ __('Discount') }}</span> <span class="number" dir="ltr"><span
                          class="text-success">-</span>
                        {{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                        <span class="shop_discount">{{ Session::get('Shop_discount') }}</span>
                        {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}</span>
                    </li>
                  @endif


                  <li><span class="text">{{ __('Shipping Cost') }}</span> <span class="number" dir="ltr">
                      <span class="text-danger">+</span>
                      {{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                      <span
                        class="shipping_cost">{{ Session::get('shipping_cost') != null ? Session::get('shipping_cost') : $shipping_cost }}</span>
                      {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}</span>
                  </li>


                  @php
                    $tax = App\Models\BasicSettings\Basic::select('shop_tax')->first();
                    $tax_percentage = $tax->shop_tax;
                    $total_tax_amount = ($tax_percentage / 100) * ($cartTotal - Session::get('Shop_discount'));
                  @endphp
                  <li><span class="text">{{ __('Tax') . '(' . $tax_percentage . '%)' }}</span>

                    <span class="number" dir="ltr"><span class="text-danger">+</span>
                      {{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                      {{ $total_tax_amount }}
                      {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}</span>
                  </li>

                  <li><span class="text">{{ __('Grand Total') }}</span> <span class="number"
                      dir="ltr">{{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                      @php
                        $charge = Session::get('shipping_cost') != null ? Session::get('shipping_cost') : $shipping_cost;
                      @endphp
                      <span class="grand_total"
                        dir="ltr">{{ $charge + $cartTotal + $total_tax_amount - Session::get('Shop_discount') }}</span>
                      {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}</span>
                  </li>
                </ul>
              </div>
            </div>
            <div class="coupon">
              <h4 class="mb-3">{{ __('Coupon') }}</h4>
              <div class="input-group d-flex">
                <input type="text" class="form-control" name="coupon" id="coupon-code" value="">
                <div class="input-group-append">
                  <button class="btn theme-btn base-btn2" type="button">{{ __('Apply') }}</button>
                </div>
              </div>
            </div>
            <h5 class="from-title mt-20 mb-15">{{ __('Payment Method') }}</h5>
            <div class="form-group">
              <select name="gateway" id="payment">
                <option disabled selected>{{ __('Choose an Option') }}</option>
                @php
                  $offline_gateways = App\Models\PaymentGateway\OfflineGateway::where('status', 1)
                      ->orderBy('serial_number', 'asc')
                      ->get();
                  $online_gateways = App\Models\PaymentGateway\OnlineGateway::where('status', 1)->get();
                @endphp
                @foreach ($online_gateways as $online_gateway)
                  <option value="{{ $online_gateway->keyword }}"
                    {{ $online_gateway->keyword == old('gateway') ? 'selected' : '' }}>
                    {{ __($online_gateway->name) }}</option>
                @endforeach
                @foreach ($offline_gateways as $offline_gateway)
                  <option value="{{ $offline_gateway->id }}"
                    {{ $offline_gateway->id == old('gateway') ? 'selected' : '' }}>
                    {{ __($offline_gateway->name) }}</option>
                @endforeach
              </select>
              @error('gateway')
                <p class="text-danger">{{ $message }}</p>
              @enderror
              @if (Session::has('error'))
                <p class="text-danger">{{ Session::get('error') }}</p>
              @enderror
          </div>

          <div id="stripe-element" class="mb-2">
            <!-- A Stripe Element will be inserted here. -->
          </div>
          <!-- Used to display form errors -->
          <div id="stripe-errors" role="alert" class="mb-2"></div>

          @foreach ($offline_gateways as $offlineGateway)
            <div class="@if (
                $errors->has('attachment') &&
                    request()->session()->get('gatewayId') == $offlineGateway->id) d-block @else d-none @endif offline-gateway-info"
              id="{{ 'offline-gateway-' . $offlineGateway->id }}">
              @if (!is_null($offlineGateway->short_description))
                <div class="form-group mb-4">
                  <label>{{ __('Description') }}</label>
                  <p>{{ $offlineGateway->short_description }}</p>
                </div>
              @endif

              @if (!is_null($offlineGateway->instructions))
                <div class="form-group mb-4">
                  <label>{{ __('Instructions') }}</label>
                  <div class="summernote-content">
                    {!! $offlineGateway->instructions !!}
                  </div>
                </div>
              @endif

              @if ($offlineGateway->has_attachment == 1)
                <div class="form-group mb-4">
                  <label>{{ __('Attachment') . '*' }}</label>
                  <br>
                  <input type="file" name="attachment">
                  @error('attachment')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              @endif
            </div>
          @endforeach
          <button type="submit" class="theme-btn w-100">{{ __('Proceed to Payment') }}</button>
        </div>
      </div>
    </form>
  </div>
</section>
<!-- CheckOut Area End -->
@endsection

@php
  $stripe = App\Models\PaymentGateway\OnlineGateway::where('keyword', 'stripe')->first();
  $stripe_info = json_decode($stripe->information, true);
  $stripe_key = $stripe_info['key'];
@endphp

@section('script')
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('assets/front/js/custom.js') }}"></script>
<script>
  let coupon_url = "{{ route('shop.apply-coupon') }}";
  let stripe_key = "{{ $stripe_key }}";
</script>
<script src="{{ asset('assets/front/js/product_checkout.js') }}"></script>
@endsection
