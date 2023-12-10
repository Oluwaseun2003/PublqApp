@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Order Details') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Product Order') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Order Details') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    @php
      $position = $order->currency_symbol_position;
      $currency = $order->currency_symbol;
    @endphp

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">
                {{ __('Order ID') . ' ' . ' #' . $order->order_number }}
              </div>

            </div>
            <div class="col-lg-4">
              <a class="btn btn-info btn-sm float-right d-inline-block mr-2" href="{{ route('admin.product.order') }}">
                <span class="btn-label">
                  <i class="fas fa-backward"></i>
                </span>
                {{ __('Back') }}
              </a>
            </div>
          </div>

        </div>

        <div class="card-body">
          <div class="payment-information">


            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Sub Total') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($order->cart_total))
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $order->cart_total }}{{ $position == 'right' ? ' ' . $currency : '' }}
                @else
                  -
                @endif
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Coupoun Appy Discount') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($order->discount))
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $order->discount }}{{ $position == 'right' ? ' ' . $currency : '' }}
                @else
                  -
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Tax') . '(' . $order->tax_percentage . '%) :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($order->tax_percentage))
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ ($order->tax_percentage / 100) * ($order->cart_total - $order->discount) }}{{ $position == 'right' ? ' ' . $currency : '' }}
                @else
                  -
                @endif
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Shipping Charges') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($order->shipping_charge))
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $order->shipping_charge }}{{ $position == 'right' ? ' ' . $currency : '' }}
                @else
                  -
                @endif
              </div>
            </div>


            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Total Paid') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($order->total))
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $order->total }}{{ $position == 'right' ? ' ' . $currency : '' }}
                @else
                  -
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Paid via') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($order->method))
                  {{ $order->method }}
                @else
                  -
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Payment Status') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if ($order->payment_status == 'completed')
                  <span class="badge badge-success">{{ __('Completed') }}</span>
                @elseif ($order->payment_status == 'pending')
                  <span class="badge badge-warning">{{ __('Pending') }}</span>
                @elseif ($order->payment_status == 'rejected')
                  <span class="badge badge-danger">{{ __('Rejected') }}</span>
                @else
                  -
                @endif
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <strong>{{ __('Order Date') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ date_format($order->created_at, 'M d, Y') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Billing Details') }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Name') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $order->billing_fname . ' ' . $order->billing_lname }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Email') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $order->billing_email }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Phone') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $order->billing_phone }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Address') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $order->billing_address }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('City') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $order->billing_city }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('State') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($order->billing_state))
                  {{ $order->billing_state }}
                @else
                  -
                @endif
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <strong>{{ __('Country') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $order->billing_country }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Shipping Details') }}
          </div>
        </div>

        <div class="card-body">
          <div class="payment-information">
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Name') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $order->shipping_fname . ' ' . $order->shipping_lname }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Email') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $order->shipping_email }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Phone') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $order->shipping_phone }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Address') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $order->shipping_address }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('City') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $order->shipping_city }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('State') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($order->shipping_state))
                  {{ $order->shipping_state }}
                @else
                  -
                @endif
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <strong>{{ __('Country') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $order->shipping_country }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @if (count($order->order_items) > 0)
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="card-title d-inline-block">
              {{ __('Product Info') }}
            </div>
          </div>

          <div class="card-body">
            <div class="payment-information">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>{{ __('Product Name') }}</th>
                    <th>{{ __('Image') }}</th>
                    <th>{{ __('SKU') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('Price') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($order->order_items as $item)
                    <tr>
                      <td>{{ $item->title }}</td>
                      <td>
                        <img class="h80" src="{{ asset('assets/admin/img/product/feature_image/' . $item->image) }}"
                          alt="">
                      </td>
                      <td>{{ $item->sku }}</td>
                      <td>{{ $item->qty }}</td>
                      <td>
                        {{ $position == 'left' ? $currency . ' ' : '' }}{{ $item->price }}{{ $position == 'right' ? ' ' . $currency : '' }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    @endif

  </div>
@endsection
