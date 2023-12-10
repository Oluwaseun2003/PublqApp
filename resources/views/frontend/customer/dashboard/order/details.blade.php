@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->customer_order_details_page_title ?? __('Order Details') }}
  @else
    {{ __('Order Details') }}
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
            {{ $pageHeading->customer_order_details_page_title ?? __('Order Details') }}
          @else
            {{ __('Order Details') }}
          @endif
        </h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">
                @if (!empty($pageHeading))
                  {{ $pageHeading->customer_dashboard_page_title ?? __('Dashboard') }}
                @else
                  {{ __('Dashboard') }}
                @endif
              </a></li>
            <li class="breadcrumb-item active">
              @if (!empty($pageHeading))
                {{ $pageHeading->customer_order_details_page_title ?? __('Order Details') }}
              @else
                {{ __('Order Details') }}
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
  <!--====== Start Dashboard Section ======-->
  <section class="user-dashbord">
    <div class="container">
      <div class="row">
        @includeIf('frontend.customer.partials.sidebar')
        <div class="col-lg-9">
          <div class="row">
            <div class="col-lg-12">
              <div class="user-profile-details">
                <div class="order-details">
                  <div class="title">
                    <div class="row">
                      <div class="col-lg-9">
                        <h4>{{ __('My Order details') }}</h4>
                      </div>
                      <div class="col-lg-3">
                        <a href="{{ route('customer.my_orders') }}" class="btn float-right">
                          {{ __('back') }}</a>
                      </div>
                    </div>
                  </div>
                  <div class="view-order-page">
                    <div class="order-info-area">
                      <div class="row align-items-center">
                        <div class="col-lg-9">
                          <div class="order-info">
                            <h3>{{ __('Order ID') . ' # ' }} {{ $order->order_number }}
                              [{{ __($order->order_status) }}]
                            </h3>
                            <p><b>{{ __('Order Date') . ' : ' }}</b>
                              {{ \Carbon\Carbon::parse($order->created_at)->translatedFormat('d-M-Y') }}
                            </p>
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="prinit">
                            @if ($order->invoice_number != null)
                              <a href="{{ asset('assets/admin/file/order/invoices/' . $order->invoice_number) }}"
                                download class="btn"><i class="fas fa-download"></i>{{ __('Download') }}</a>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="billing-add-area">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="main-info">
                          <h5>{{ __('Billing Information') }}</h5>
                          <ul class="list">
                            <li>
                              <p><b>{{ __('Name') . ' : ' }} </b>{{ $order->billing_fname }}
                                {{ $order->billing_lname }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Email') . ' : ' }}
                                </b>{{ $order->billing_email }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Phone') . ' : ' }}</b>{{ $order->billing_phone }}
                              </p>
                            </li>
                            <li>
                              <p><b>{{ __('Country') . ' : ' }}
                                </b>{{ $order->billing_country }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('State') . ' : ' }}</b>{{ $order->billing_state }}
                              </p>
                            </li>
                            <li>
                              <p><b>{{ __('City') . ' : ' }} </b>{{ $order->billing_city }}
                              </p>
                            </li>
                            <li>
                              <p><b>{{ __('Zip-code') . ' : ' }}
                                </b>{{ $order->billing_zip_code }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Address') . ' : ' }} </b>
                                {{ $order->billing_address }}</p>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="main-info">
                          <h5>{{ __('Shipping Information') }}</h5>
                          <ul class="list">
                            <li>
                              <p><b>{{ __('Name') . ' : ' }}
                                </b>{{ $order->shipping_fname }}
                                {{ $order->shipping_lname }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Email') . ' : ' }}
                                </b>{{ $order->shipping_email }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Phone') . ' : ' }}
                                </b>{{ $order->shipping_phone }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Country') . ' : ' }}
                                </b>{{ $order->shipping_country }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('State') . ' : ' }}
                                </b>{{ $order->shipping_state }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('City') . ' : ' }} </b>{{ $order->shipping_city }}
                              </p>
                            </li>
                            <li>
                              <p><b>{{ __('Zip-code') . ' : ' }}
                                </b>{{ $order->shipping_zip_code }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Address') . ' : ' }} </b>
                                {{ $order->shipping_address }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Shipping Status') . ' : ' }} </b>
                                @php
                                  $order_items = App\Models\ShopManagement\OrderItem::where('product_order_id', $order->id)
                                      ->select('product_id')
                                      ->get();
                                  $only_digital = true;
                                  foreach ($order_items as $key => $order_item) {
                                      $product = App\Models\ShopManagement\Product::where('id', $order_item->product_id)
                                          ->select('type')
                                          ->first();
                                      if ($product->type == 'physical') {
                                          $only_digital = false;
                                      }
                                  }
                                @endphp
                                @if ($only_digital == false && $order->payment_status != 'rejected')
                                  @if ($order->order_status == 'completed')
                                    <span class="badge badge-success">
                                      {{ __($order->order_status) }}
                                    </span>
                                  @elseif ($order->order_status == 'processing')
                                    <span class="badge badge-info">
                                      {{ __($order->order_status) }}
                                    </span>
                                  @elseif ($order->order_status == 'pending')
                                    <span class="badge badge-warning">
                                      {{ __($order->order_status) }}
                                    </span>
                                  @else
                                    <span class="badge badge-danger">
                                      {{ __($order->order_status) }}
                                    </span>
                                  @endif
                                @else
                                  -
                                @endif
                              </p>
                            </li>
                          </ul>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <hr>
                        <div class="payment-information">
                          <h5>{{ __('Payment Status') }}</h5>
                          <p><b>{{ __('Payment Status') . ' : ' }}</b> <span
                              class="badge {{ $order->payment_status == 'completed' ? 'badge-success' : 'badge-danger' }} ">{{ __($order->payment_status) }}</span>
                          </p>
                          <p><b>{{ __('Paid Amount') . ' : ' }}</b> <span class="amount">
                              {{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}
                              {{ $order->total }}
                              {{ $order->currency_symbol_position == 'right' ? $order->currency_symbol : '' }}
                            </span>
                          </p>
                          @if ($order->discount != null)
                            <p><b>{{ __('Discount') . ' : ' }}</b> <span
                                class="amount">{{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}
                                {{ $order->discount }}
                                {{ $order->currency_symbol_position == 'right' ? $order->currency_symbol : '' }}</span>
                            </p>
                          @endif
                          @if ($order->shipping_charge != null)
                            <p><b>{{ __('Shipping Charge') . ' : ' }}</b> <span
                                class="amount">{{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}
                                {{ $order->shipping_charge }}
                                {{ $order->currency_symbol_position == 'right' ? $order->currency_symbol : '' }}</span>
                            </p>
                          @endif
                          @if ($order->tax != null)
                            <p><b>{{ __('Tax') . '(' . $order->tax_percentage . '%) :' }}</b> <span
                                class="amount">{{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}
                                {{ ($order->tax_percentage / 100) * ($order->cart_total - $order->discount) }}
                                {{ $order->currency_symbol_position == 'right' ? $order->currency_symbol : '' }}</span>
                            </p>
                          @endif

                          <p><b>{{ __('Payment Method') . ' : ' }}</b> {{ __($order->method) }}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive product-list">
                    <h5>{{ __('Product Ordered') }}</h5>
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>{{ __('Image') }}</th>
                          <th>{{ __('Product Name') }}</th>
                          <th>{{ __('Sku') }}</th>
                          <th>{{ __('Quantity') }}</th>
                          <th>{{ __('Price') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                          $order_items = App\Models\ShopManagement\OrderItem::where('product_order_id', $order->id)->get();
                        @endphp
                        @if (count($order_items) > 0)
                          @foreach ($order_items as $item)
                            <tr>
                              <td>
                                <img class="mh-60"
                                  src="{{ asset('assets/admin/img/product/feature_image/' . $item->image) }}"
                                  alt="">
                                @php
                                  $product = App\Models\ShopManagement\Product::where('id', $item->product_id)->first();
                                @endphp
                                @if ($product->type == 'digital')
                                  @if ($product->file_type == 'link')
                                    <a target="_blank" href="{{ $product->download_link }}"
                                      class="btn btn-info btn-sm">{{ __('Download') }}</a>
                                  @elseif($product->file_type == 'upload')
                                    <a href="{{ asset('assets/admin/img/product/download_file/' . $product->download_file) }}"
                                      download="" class="btn btn-info btn-sm">{{ __('Download') }}</a>
                                  @endif
                                @endif
                              </td>
                              <td>{{ \Illuminate\Support\Str::limit($item->title, 35, $end = '...') }}
                              </td>
                              <td>{{ $item->sku }}</td>
                              <td>{{ $item->qty }}</td>
                              <td>{{ $order->currency_symbol_position == 'left' ? $order->currency_symbol : '' }}
                                {{ $item->price }}
                                {{ $order->currency_symbol_position == 'right' ? $order->currency_symbol : '' }}
                              </td>
                            </tr>
                          @endforeach
                        @else
                          <h2>{{ __('No Product Found') . ' .' }}</h2>
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Dashboard Section ======-->
@endsection

@section('script')
  <script src="{{ asset('assets/front/js/page.js') }}"></script>
@endsection
