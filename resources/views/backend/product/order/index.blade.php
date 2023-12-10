@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Product Order') }}</h4>
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
        <a href="#">{{ __('Shop Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Manage Orders') }}</a>
      </li>
      @if (!request()->filled('type'))
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('All Orders') }}</a>
        </li>
      @endif
      @if (request()->filled('type') && request()->input('type') == 'pending')
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Pending Orders') }}</a>
        </li>
      @endif
      @if (request()->filled('type') && request()->input('type') == 'processing')
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Processing Orders') }}</a>
        </li>
      @endif
      @if (request()->filled('type') && request()->input('type') == 'completed')
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Completed Orders') }}</a>
        </li>
      @endif
      @if (request()->filled('type') && request()->input('type') == 'rejected')
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Rejected Orders') }}</a>
        </li>
      @endif
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('Product Order') }}</div>
            </div>

            <div class="col-lg-6 offset-lg-2">
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete ml-3 mt-1"
                data-href="{{ route('admin.product.order.bulk_delete') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>

              <form class="float-right ml-3" action="{{ route('admin.product.order') }}" method="GET">
                <input name="order_id" type="text" class="form-control" placeholder="Search By Order ID"
                  value="{{ !empty(request()->input('order_id')) ? request()->input('order_id') : '' }}">
              </form>

              <form id="searchByStatusForm" class="float-right d-flex flex-row align-items-center"
                action="{{ route('admin.product.order') }}" method="GET">
                <label class="mr-2">{{ __('Payment') }}</label>
                <select class="form-control" name="status"
                  onchange="document.getElementById('searchByStatusForm').submit()">
                  <option value="" {{ empty(request()->input('status')) ? 'selected' : '' }}>
                    {{ __('All') }}
                  </option>
                  <option value="completed" {{ request()->input('status') == 'completed' ? 'selected' : '' }}>
                    {{ __('Completed') }}
                  </option>
                  <option value="processing" {{ request()->input('status') == 'processing' ? 'selected' : '' }}>
                    {{ __('Processing') }}
                  </option>
                  <option value="pending" {{ request()->input('status') == 'pending' ? 'selected' : '' }}>
                    {{ __('Pending') }}
                  </option>
                  <option value="rejected" {{ request()->input('status') == 'rejected' ? 'selected' : '' }}>
                    {{ __('Rejected') }}
                  </option>
                </select>
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($orders) == 0)
                <h3 class="text-center mt-2">{{ __('NO PRODUCT ORDERS ARE FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Order ID') }}</th>
                        <th scope="col">{{ __('Product Name') }}</th>
                        <th scope="col">{{ __('Customer Name') }}</th>
                        <th scope="col">{{ __('Paid via') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Shipping Status') }}</th>
                        <th scope="col">{{ __('Attachment') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $order->id }}">
                          </td>
                          <td>{{ '#' . $order->order_number }}</td>
                          @php
                            $order_item = \App\Models\ShopManagement\OrderItem::where('product_order_id', $order->id)->first();
                            if (!is_null($order_item)) {
                                $product = \App\Models\ShopManagement\ProductContent::where('product_id', $order_item->product_id)
                                    ->where('language_id', $defaultLang->id)
                                    ->first();
                                if (!is_null($product)) {
                                    $title = $product->title;
                                }
                            } else {
                                $product = null;
                            }
                          @endphp

                          <td>
                            @if (!is_null($product))
                              <a href="{{ route('shop.details', ['slug' => $product->slug, 'id' => $product->product_id]) }}"
                                target="_blank">
                                {{ strlen($title) > 35 ? mb_substr($title, 0, 35, 'utf-8') . '...' : $title }}
                              </a>
                            @endif

                          </td>


                          <td>{{ $order->billing_fname }} {{ $order->billing_lname }}</td>
                          <td>{{ !is_null($order->method) ? $order->method : '-' }}</td>
                          <td>
                            @if ($order->gateway_type == 'online')
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Completed') }}</span>
                              </h2>
                            @elseif ($order->gateway_type == 'offline')
                              @if ($order->payment_status == 'pending')
                                <form id="paymentStatusForm-{{ $order->id }}" class="d-inline-block"
                                  action="{{ route('admin.order.update_payment_status', $order->id) }}" method="post">
                                  @csrf
                                  <select
                                    class="form-control form-control-sm @if ($order->payment_status == 'completed') bg-success @elseif ($order->payment_status == 'pending') bg-warning text-dark @else bg-danger @endif"
                                    name="payment_status"
                                    onchange="document.getElementById('paymentStatusForm-{{ $order->id }}').submit()">
                                    <option value="completed"
                                      {{ $order->payment_status == 'completed' ? 'selected' : '' }}>
                                      {{ __('Completed') }}
                                    </option>
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>
                                      {{ __('Pending') }}
                                    </option>
                                    <option value="rejected"
                                      {{ $order->payment_status == 'rejected' ? 'selected' : '' }}>
                                      {{ __('Rejected') }}
                                    </option>
                                  </select>
                                </form>
                              @else
                                <h2 class="d-inline-block">
                                  @if ($order->payment_status == 'completed')
                                    <span class="badge badge-success">{{ __('Completed') }}</span>
                                  @elseif ($order->payment_status == 'rejected')
                                    <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                  @endif
                                </h2>
                              @endif
                            @else
                              -
                            @endif
                          </td>
                          <td>
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
                              @if ($order->order_status == 'pending' || $order->order_status == 'processing')
                                <form id="orderStatusForm-{{ $order->id }}" class="d-inline-block"
                                  action="{{ route('admin.order.update_order_status', $order->id) }}" method="post">
                                  @csrf
                                  <select
                                    class="form-control form-control-sm @if ($order->order_status == 'completed') bg-success @elseif ($order->order_status == 'pending') bg-warning text-dark @else bg-danger @endif"
                                    name="order_status"
                                    onchange="document.getElementById('orderStatusForm-{{ $order->id }}').submit()">
                                    <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>
                                      {{ __('Pending') }}
                                    </option>
                                    <option value="processing"
                                      {{ $order->order_status == 'processing' ? 'selected' : '' }}>
                                      {{ __('Processing') }}
                                    </option>
                                    <option value="completed"
                                      {{ $order->order_status == 'completed' ? 'selected' : '' }}>
                                      {{ __('Completed') }}
                                    </option>

                                    <option value="rejected" {{ $order->order_status == 'rejected' ? 'selected' : '' }}>
                                      {{ __('Rejected') }}
                                    </option>
                                  </select>
                                </form>
                              @else
                                @if ($order->order_status == 'completed')
                                  <span class="badge badge-success">{{ __('Completed') }}</span>
                                @elseif ($order->order_status == 'rejected')
                                  <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                @endif
                              @endif
                            @else
                              -
                            @endif

                          </td>
                          <td>
                            @if (!is_null($order->receipt))
                              <a class="btn btn-sm btn-info" href="#" data-toggle="modal"
                                data-target="#attachmentModal-{{ $order->id }}">
                                {{ __('Show') }}
                              </a>
                            @else
                              -
                            @endif
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('admin.product_order.details', ['id' => $order->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>
                                @if ($order->invoice_number != null)
                                  <a href="{{ asset('assets/admin/file/order/invoices/' . $order->invoice_number) }}"
                                    class="dropdown-item" target="_blank">
                                    {{ __('Invoice') }}
                                  </a>
                                @endif

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.product.order.delete', ['id' => $order->id]) }}"
                                  method="post">

                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>
                          </td>
                        </tr>

                        @include('backend.product.order.show-attachment')
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer text-center">
          <div class="d-inline-block mt-3">
            {{ $orders->appends([
                    'order_id' => request()->input('order_id'),
                    'status' => request()->input('status'),
                ])->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
