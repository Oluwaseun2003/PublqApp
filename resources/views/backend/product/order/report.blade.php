@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">
      {{ __('Report') }}
    </h4>
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
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Report') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header p-1">
          <div class="row">
            <div class="col-lg-10">
              <form action="{{ url()->full() }}" class="form-inline">
                <div class="form-group">
                  <label for="">{{ __('From') }}</label>
                  <input class="form-control datepicker" type="text" name="from_date" placeholder="{{ __('From') }}"
                    value="{{ request()->input('from_date') ? request()->input('from_date') : '' }}" required
                    autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="">{{ __('To') }}</label>
                  <input class="form-control datepicker ml-1" type="text" name="to_date" placeholder="{{ __('To') }}"
                    value="{{ request()->input('to_date') ? request()->input('to_date') : '' }}" required
                    autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="">{{ __('Payment Method') }}</label>
                  <select name="payment_method" class="form-control ml-1">
                    <option value="" selected>{{ __('All') }}</option>
                    @if (!empty($onPms))
                      @foreach ($onPms as $onPm)
                        <option value="{{ $onPm->keyword }}"
                          {{ request()->input('payment_method') == $onPm->keyword ? 'selected' : '' }}>
                          {{ $onPm->name }}
                        </option>
                      @endforeach
                    @endif
                    @if (!empty($offPms))
                      @foreach ($offPms as $offPm)
                        <option value="{{ $offPm->name }}"
                          {{ request()->input('payment_method') == $offPm->name ? 'selected' : '' }}>{{ $offPm->name }}
                        </option>
                      @endforeach
                    @endif
                  </select>
                </div>

                <div class="form-group">
                  <label for="">{{ __('Payment Status') }}</label>
                  <select name="payment_status" class="form-control ml-1">
                    <option value="" selected>All</option>
                    <option value="Pending" {{ request()->input('payment_status') == 'Pending' ? 'selected' : '' }}>
                      {{ __('Pending') }}</option>
                    <option value="Completed" {{ request()->input('payment_status') == 'Completed' ? 'selected' : '' }}>
                      {{ __('Completed') }}</option>
                  </select>
                </div>

                <div class="form-group">
                  <button type="submit" class="btn btn-primary btn-sm ml-1">{{ __('Submit') }}</button>
                </div>
              </form>
            </div>
            <div class="col-lg-2">
              <form action="{{ route('admin.product_order.export') }}" class="form-inline justify-content-end">
                <div class="form-group">
                  <button type="submit" class="btn btn-success btn-sm ml-1"
                    title="CSV Format">{{ __('Export') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($orders) > 0)
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Order ID') }}</th>
                        <th scope="col">{{ __('Customer Name') }}</th>
                        <th scope="col">{{ __('Discount') }}</th>
                        <th scope="col">{{ __('Shipping Charges') }}</th>
                        <th scope="col">{{ __('Total') }}</th>
                        <th scope="col">{{ __('Gateway') }}</th>
                        <th scope="col">{{ __('Payment') }}</th>
                        <th scope="col">{{ __('Date') }}</th>
                        <th scope="col">{{ __('Receipt') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $key => $order)
                        <tr>
                          <td>#{{ $order->order_number }}</td>
                          <td>{{ $order->billing_fname }} {{ $order->billing_lname }}</td>

                          <td>
                            {{ $abs->base_currency_symbol_position == 'left' ? $abs->base_currency_symbol : '' }}{{ round($order->discount, 2) }}{{ $abs->base_currency_symbol_position == 'right' ? $abs->base_currency_symbol : '' }}
                          </td>

                          <td>
                            {{ $abs->base_currency_symbol_position == 'left' ? $abs->base_currency_symbol : '' }}{{ round($order->shipping_charge, 2) }}{{ $abs->base_currency_symbol_position == 'right' ? $abs->base_currency_symbol : '' }}
                          </td>

                          <td>
                            {{ $abs->base_currency_symbol_position == 'left' ? $abs->base_currency_symbol : '' }}{{ round($order->total, 2) }}{{ $abs->base_currency_symbol_position == 'right' ? $abs->base_currency_symbol : '' }}
                          </td>


                          <td>{{ ucfirst($order->method) }}</td>
                          <td>
                            @if ($order->payment_status == 'pending')
                              <span class="badge badge-warning">{{ __('Pending') }}</span>
                            @elseif ($order->payment_status == 'completed')
                              <span class="badge badge-success">{{ __('Completed') }}</span>
                            @endif
                          </td>
                          <td>
                            {{ $order->created_at }}
                          </td>
                          <td>
                            <a href="javascript:void(0)" data-toggle="modal"
                              data-target="#receiptModal{{ $order->id }}"
                              class="btn btn-info btn-sm">{{ __('View') }}</a>
                          </td>
                        </tr>


                        {{-- Receipt Modal --}}
                        <div class="modal fade" id="receiptModal{{ $order->id }}" tabindex="-1" role="dialog"
                          aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{ __('Receipt Image') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <iframe src="{{ asset('assets/admin/file/order/invoices/' . $order->invoice_number) }}"
                                  frameborder="0" class="receipt"></iframe>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                  data-dismiss="modal">{{ __('Close') }}</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        @if (!empty($orders))
          <div class="card-footer">
            <div class="row">
              <div class="d-inline-block mx-auto">
                {{ $orders->links() }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>

@endsection
