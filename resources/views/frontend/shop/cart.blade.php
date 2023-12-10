@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->cart_page_title ?? __('Cart') }}
  @else
    {{ __('Cart') }}
  @endif
@endsection
@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">
          @if (!empty($pageHeading))
            {{ $pageHeading->cart_page_title ?? __('Cart') }}
          @else
            {{ __('Cart') }}
          @endif
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">
              @if (!empty($pageHeading))
                {{ $pageHeading->cart_page_title ?? __('Cart') }}
              @else
                {{ __('Cart') }}
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
  <section class="cart-page py-120 rpy-100">
    <div class="container">
      <div class="cart-total-product">
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

          <div class="total-cart-price">
            <h6>{{ __('Total Items') }}: <span class="cart-item-view"
                dir="ltr">{{ $cart_items ? $countitem : 0 }}</span> </h6>
            <h6>
              <strong>{{ __('Cart Total') }} :</strong> <strong class="cart-total-view"
                dir="ltr">{{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                {{ $cartTotal }}
                {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}</strong>
            </h6>
          </div>
        @endif
        @if ($cart_items != null)
          <div class="cart-title">
            <span class="product-title">{{ __('Product') }}</span>
            <span class="quantity-title">{{ __('Quantity') }}</span>
            <span class="avilable-title">{{ __('Availability') }}</span>
            <span class="price-title">{{ __('Price') }}</span>
            <span class="total-title">{{ __('Total') }}</span>
            <span class="remove-title">{{ __('Remove') }}</span>
          </div>
          <div class="cart-item-wrap pt-15">
            @foreach ($cart_items as $id => $item)
              @php
                $product = App\Models\ShopManagement\Product::where('id', $id)->first();
              @endphp
              <div class="alert fade show cart-single-item">
                <h6 class="product-name">
                  {{ \Illuminate\Support\Str::limit($item['name'], 35, $end = '...') }}</h6>
                <div class="quantity-input">
                  <button class="quantity-down" id="quantityDown">
                    -
                  </button>
                  <input id="quantity" class="cart_qty" type="number" value="{{ $item['qty'] }}" name="quantity">
                  <button class="quantity-up" id="quantityUP">
                    +
                  </button>
                </div>
                <input type="hidden" value="{{ $id }}" class="product_id">
                @if ($product->type == 'digital')
                  <span class="avilable"><i class="fas fa-check"></i> {{ __('Available Now') }}</span>
                @else
                  @if ($product->stock >= $item['qty'])
                    <span class="avilable"><i class="fas fa-check"></i> {{ __('Available Now') }}</span>
                  @else
                    <span class="avilable text-danger"><i class="text-danger fas fa-times"></i>
                      {{ __('Out Of Stock Now') }}</span>
                  @endif
                @endif

                <span class="product-price"
                  dir="ltr">{{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                  <span>{{ $item['price'] }}</span>
                  {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}</span>
                <span class="product-total-price" dir="ltr">
                  {{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                  <span>{{ $item['qty'] * $item['price'] }}</span>
                  {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}
                </span>
                <button type="button" class="close item-remove" data-dismiss="alert" rel="{{ $id }}"
                  data-href="{{ route('cart.item.remove', $id) }}"><span aria-hidden="true">&times;</span></button>
              </div>
            @endforeach
          </div>
        @endif

      </div>

      <div class="table-outer"></div>

      @if ($cart_items == null)
        <div class="bg-light py-5 text-center">
          <h3 class="text-uppercase">{{ __('Cart is empty') . ' !' }}</h3>
        </div>
      @endif
      @if ($cart_items != null)
        <div class="cart-total-price mt-40">
          <div class="row justify-content-end text-center text-lg-left">
            <div class="col-lg-6">
              <div class="update-shopping text-lg-right">
                @csrf
                <a id="cartUpdate" data-href="{{ route('cart.update') }}"
                  class="theme-btn mt-10">{{ __('update cart') }}</a>
                <a href="{{ route('shop.checkout') }}" class="theme-btn mt-10">{{ __('checkout') }}</a>
              </div>
            </div>
          </div>
        </div>
      @endif
    </div>
  </section>
@endsection

@section('script')
  <script>
    var symbol = "{{ $basicInfo->base_currency_symbol }}";
    var position = "{{ $basicInfo->base_currency_symbol_position }}";
  </script>
@endsection
