@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->customer_order_page_title ?? __('My Orders') }}
  @else
    {{ __('My Orders') }}
  @endif
@endsection
@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="banner-inner">
      <h2 class="page-title">
        @if (!empty($pageHeading))
          {{ $pageHeading->customer_order_page_title ?? __('My Orders') }}
        @else
          {{ __('My Orders') }}
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
              {{ $pageHeading->customer_booking_page_title ?? __('My Orders') }}
            @else
              {{ __('My Orders') }}
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
                <div class="account-info">
                  <div class="title">
                    <h4>{{ __('Recent Orders') }}</h4>
                  </div>
                  <div class="main-info">
                    <div class="main-table">
                      <div class="table-responsiv">
                        <table id="example" class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                          <thead>
                            <tr>
                              <th>{{ __('Order Id') }}</th>
                              <th>{{ __('Date') }}</th>
                              <th>{{ __('Payment Status') }}</th>
                              <th>{{ __('Shipping Status') }}</th>
                              <th>{{ __('Action') }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($orders as $item)
                              <tr>
                                <td># {{ $item->order_number }}</td>
                                <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
                                <td>
                                  <span
                                    class="pending @if ($item->payment_status == 'completed') bg-success
                                  @elseif ($item->payment_status == 'pending')
                                  bg-info
                                  @else
                                    bg-danger @endif ">{{ __($item->payment_status) }}</span>
                                </td>
                                <td>
                                  @php
                                    $order_items = App\Models\ShopManagement\OrderItem::where('product_order_id', $item->id)
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
                                  @if ($only_digital == false && $item->payment_status != 'rejected')
                                    <span
                                      class="pending @if ($item->order_status == 'completed') bg-success
                                  @elseif ($item->order_status == 'processing')
                                  bg-info
                                  @elseif ($item->order_status == 'pending')
                                  bg-warning
                                  @else
                                    bg-danger @endif ">{{ __($item->order_status) }}</span>
                                  @else
                                    -
                                  @endif
                                </td>

                                <td><a href="{{ route('customer.order_details', $item->id) }}"
                                    class="btn">{{ __('Details') }}</a></td>
                              </tr>
                            @endforeach
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
      </div>
    </div>
  </section>
  <!--====== End Dashboard Section ======-->
@endsection
