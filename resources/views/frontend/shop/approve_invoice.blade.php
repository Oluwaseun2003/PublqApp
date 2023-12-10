<!DOCTYPE html>
<html>

<head lang="{{ $currentLanguageInfo->code }}" @if ($currentLanguageInfo->direction == 1) dir="rtl" @endif>
  {{-- required meta tags --}}
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  {{-- title --}}
  <title>{{ 'Invoice | ' . config('app.name') }}</title>

  {{-- fav icon --}}
  <link rel="shortcut icon" type="image/png" href="{{ asset('assets/admin/img/' . $websiteInfo->favicon) }}">

  {{-- styles --}}
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
  @php
    $wd_100 = '100%';
    $w_47 = '47%';
    $_15px = '15px';
    $_12px = '12px';
  @endphp

  <style>
    body {
      font-size: {{ $_15px }};
    }

    p {
      font-size: {{ $_12px }};
      margin-bottom: {{ $_12px }};
    }
  </style>
</head>

<body>
  <div class="my-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="logo text-center" style="margin-bottom: {{ $mb }};">
            <img src="{{ asset('assets/admin/img/' . $websiteInfo->logo) }}" alt="Company Logo">
          </div>

          <div class="bg-primary">
            <h2 class="text-center text-light pt-2 pb-2">
              {{ __('Booking Invoice') }}
            </h2>
          </div>

          <div class="row">
            {{-- enrolment details start --}}
            <div style="width: {{ $wd_100 }};">
              <div class="mb-2 mt-3">
                <table class="table">
                  @php
                    $cart_items = App\Models\ShopManagement\OrderItem::where('product_order_id', $orderInfo->id)->get();
                    $cartTotal = 0;
                    $countitem = 0;
                    if ($cart_items) {
                        foreach ($cart_items as $p) {
                            $cartTotal += $p->price * $p->qty;
                            $countitem += $p->qty;
                        }
                    }
                  @endphp
                  <thead>
                    <tr>
                      <th>{{ __('Product') }}</th>
                      <th>{{ __('Quantity') }}</th>
                      <th>{{ __('Total') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($cart_items as $id => $item)
                      @php
                        $product = App\Models\ShopManagement\Product::where('id', $id)->first();
                      @endphp
                      <tr>
                        <td>{{ \Illuminate\Support\Str::limit($item['name'], 35, $end = '...') }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>
                          {{ $basicInfo->base_currency_symbol_position == 'left' ? $basicInfo->base_currency_symbol : '' }}
                          <span>{{ $item['qty'] * $item['price'] }}</span>
                          {{ $basicInfo->base_currency_symbol_position == 'right' ? $basicInfo->base_currency_symbol : '' }}
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            {{-- enrolment details start --}}
          </div>

          <div class="clear-fix">
            <div class="float-left" style="width: {{ $w_47 }}">
              <div class="mb-1">
                <h6><strong>{{ __('Order Details') }}</strong></h6>
              </div>
              <p>
                <strong>{{ __('Order ID') . ': ' }}</strong>{{ '#' . $orderInfo->order_number }}
              </p>
              <p>
                <strong>{{ __('Order Date') . ': ' }}</strong>{{ date_format($orderInfo->created_at, 'M d, Y') }}
              </p>
              <p>
                <strong>{{ __('Shipping Charge') . ': ' }}
                  {{ $orderInfo->currency_symbol_position == 'left' ? $orderInfo->currency_symbol : '' }}
                  {{ $orderInfo->shipping_charge }}
                  {{ $orderInfo->currency_symbol_position == 'right' ? $orderInfo->currency_symbol : '' }} </strong>
              </p>
              <p>
                <strong>{{ __('Total Price') . ': ' }}
                  {{ $orderInfo->currency_symbol_position == 'left' ? $orderInfo->currency_symbol : '' }}
                  {{ $orderInfo->total }}
                  {{ $orderInfo->currency_symbol_position == 'right' ? $orderInfo->currency_symbol : '' }}</strong>
              </p>
              <p>
                <strong>{{ __('Coupon Discount') . ': ' }}</strong>{{ $orderInfo->currency_symbol_position == 'left' ? $orderInfo->currency_symbol : '' }}
                {{ $orderInfo->discount != null ? $orderInfo->discount : '0' }}
                {{ $orderInfo->currency_symbol_position == 'right' ? $orderInfo->currency_symbol : '' }}
              </p>
              <p>
                <strong>{{ __('Payment Method') . ': ' }}</strong>{{ $orderInfo->method }}
              </p>
              <p>
                <strong>{{ __('Payment Status') . ': ' }}</strong>
                @if ($orderInfo->payment_status == 'completed')
                  {{ __('Completed') }}
                @elseif ($orderInfo->payment_status == 'pending')
                  {{ __('Pending') }}
                @elseif ($orderInfo->payment_status == 'rejected')
                  {{ __('Rejected') }}
                @else
                  -
                @endif
              </p>
            </div>
            {{-- billing details start --}}
            <div class="float-right" style="width: {{ $w_47 }}">
              <div class="mb-1">
                <h6><strong>{{ __('Billing Details') }}</strong></h6>
              </div>
              <p>
                <strong>{{ __('Name') . ': ' }}</strong>{{ $orderInfo->billing_fname . ' ' . $orderInfo->billing_lname }}
              </p>
              <p>
                <strong>{{ __('Email') . ': ' }}</strong>{{ $orderInfo->billing_email }}
              </p>
              <p>
                <strong>{{ __('Contact Number') . ': ' }}</strong>{{ $orderInfo->billing_phone }}
              </p>
              <p>
                <strong>{{ __('Address') . ': ' }}</strong>{{ $orderInfo->billing_address }}
              </p>
              <p>
                <strong>{{ __('City') . ': ' }}</strong>{{ $orderInfo->billing_city }}
              </p>
              <p>
                <strong>{{ __('State') . ': ' }}</strong>{{ is_null($orderInfo->billing_state) ? '-' : $orderInfo->billing_state }}
              </p>
              <p>
                <strong>{{ __('Country') . ': ' }}</strong>{{ $orderInfo->billing_country }}
              </p>
            </div>
            {{-- billing details end --}}
            {{-- shipping details start --}}
            <div style="width: {{ $wd_100 }}">
              <div class="mt-4 mb-1">
                <h6><strong>{{ __('Shipping Details') }}</strong></h6>
              </div>

              <p>
                <strong>{{ __('Name') . ': ' }}</strong>{{ $orderInfo->shipping_fname . ' ' . $orderInfo->shipping_lname }}
              </p>

              <p>
                <strong>{{ __('Email') . ': ' }}</strong>{{ $orderInfo->shipping_email }}
              </p>

              <p>
                <strong>{{ __('Contact Number') . ': ' }}</strong>{{ $orderInfo->shipping_phone }}
              </p>

              <p>
                <strong>{{ __('Address') . ': ' }}</strong>{{ $orderInfo->shipping_address }}
              </p>

              <p>
                <strong>{{ __('City') . ': ' }}</strong>{{ $orderInfo->shipping_city }}
              </p>

              <p>
                <strong>{{ __('State') . ': ' }}</strong>{{ is_null($orderInfo->shipping_state) ? '-' : $orderInfo->shipping_state }}
              </p>

              <p>
                <strong>{{ __('Country') . ': ' }}</strong>{{ $orderInfo->shipping_country }}
              </p>
            </div>
            {{-- shipping details end --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
