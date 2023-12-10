@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Transactions') }}</h4>
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
        <a href="#">{{ __('Transactions') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Transactions') }}</div>
            </div>

            <div class="col-lg-4">
              <form action="" method="get">
                <input type="text" value="{{ request()->input('transcation_id') }}" name="transcation_id"
                  placeholder="Enter Transaction Id" class="form-control">
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($transcations) == 0)
                <h3 class="text-center mt-3">{{ __('NO TRANSACTION FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Transaction Id') }}</th>
                        <th scope="col">{{ __('Organizer') }}</th>
                        <th scope="col">{{ __('Transaction Type') }}</th>
                        <th scope="col">{{ __('Payment Method') }}</th>
                        <th scope="col">{{ __('Pre Balance') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                        <th scope="col">{{ __('After Balance') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($transcations as $transcation)
                        <tr>
                          <td>#{{ $transcation->transcation_id }}</td>
                          <td>
                            @php
                              $organizer = $transcation->organizer()->first();
                            @endphp
                            @if ($organizer)
                              <a target="_blank"
                                href="{{ route('admin.organizer_management.organizer_details', ['id' => $organizer->id, 'language' => $defaultLang->code]) }}">{{ $organizer->username }}</a>
                            @else
                              <span class="badge badge-success">{{ __('Admin') }}</span>
                            @endif
                          </td>
                          <td>
                            @if ($transcation->transcation_type == 1)
                              {{ 'Event Booking' }}
                            @elseif ($transcation->transcation_type == 2)
                              {{ 'Product Order' }}
                            @elseif ($transcation->transcation_type == 3)
                              {{ 'Withdraw' }}
                            @elseif ($transcation->transcation_type == 4)
                              {{ 'Balance Add' }}
                            @elseif ($transcation->transcation_type == 5)
                              {{ 'Balance Subtract' }}
                            @endif
                          </td>
                          <td>
                            @if ($transcation->transcation_type == 3)
                              @php
                                $method = $transcation->method()->first();
                              @endphp
                              @if ($method)
                                {{ $method->name }}
                              @else
                                {{ '-' }}
                              @endif
                            @else
                              {{ $transcation->payment_method != null ? $transcation->payment_method : '-' }}
                            @endif
                          </td>
                          <td>
                            {{ $transcation->currency_symbol_position == 'left' ? $transcation->currency_symbol : '' }}
                            {{ $transcation->pre_balance }}
                            {{ $transcation->currency_symbol_position == 'right' ? $transcation->currency_symbol : '' }}
                          </td>
                          <td>
                            @if ($transcation->transcation_type == 3 || $transcation->transcation_type == 5)
                              <span class="text-danger">{{ '(-)' }}</span>
                            @else
                              <span class="text-success">{{ '(+)' }}</span>
                            @endif

                            {{ $transcation->currency_symbol_position == 'left' ? $transcation->currency_symbol : '' }}
                            {{ $transcation->grand_total - $transcation->commission }}
                            {{ $transcation->currency_symbol_position == 'right' ? $transcation->currency_symbol : '' }}
                          </td>
                          <td>
                            {{ $transcation->currency_symbol_position == 'left' ? $transcation->currency_symbol : '' }}
                            {{ $transcation->after_balance }}
                            {{ $transcation->currency_symbol_position == 'right' ? $transcation->currency_symbol : '' }}
                          </td>
                          <td>
                            @if ($transcation->payment_status == 1)
                              <span class="badge badge-success">{{ __('Paid') }}</span>
                            @elseif ($transcation->payment_status == 2)
                              <span class="badge badge-warning">{{ __('Decline') }}</span>
                            @else
                              <span class="badge badge-danger">{{ __('Unpaid') }}</span>
                            @endif
                          </td>

                          <td>
                            @if ($transcation->transcation_type == 1)
                              @php
                                $t_invoice = $transcation->event_booking()->first();
                              @endphp
                              @if ($t_invoice)
                                <a target="_blank" class="btn btn-secondary btn-sm mr-1"
                                  href="{{ asset('assets/admin/file/invoices/' . $t_invoice->invoice) }}">
                                  <i class="fas fa-eye"></i>
                                </a>
                              @else
                                {{ '-' }}
                              @endif
                            @elseif ($transcation->transcation_type == 2)
                              @php
                                $t_invoice = $transcation->product_order()->first();
                              @endphp
                              @if ($t_invoice)
                                <a target="_blank" class="btn btn-secondary btn-sm mr-1"
                                  href="{{ asset('assets/admin/file/order/invoices/' . $t_invoice->invoice) }}">
                                  <i class="fas fa-eye"></i>
                                </a>
                              @else
                                {{ '-' }}
                              @endif
                            @else
                              {{ '-' }}
                            @endif
                          </td>
                        </tr>
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
            {{ $transcations->appends([
                    'transcation_id' => request()->input('transcation_id'),
                ])->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
