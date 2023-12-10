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
        <a href="#">{{ __('Event Bookings') }}</a>
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
                  <input class="form-control datepicker" type="text" name="from_date" placeholder="From"
                    value="{{ request()->input('from_date') ? request()->input('from_date') : '' }}" required
                    autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="">{{ __('To') }}</label>
                  <input class="form-control datepicker ml-1" type="text" name="to_date" placeholder="To"
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
                    <option value="" selected>{{ __('All') }}</option>
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
              <form action="{{ route('admin.event_bookings.export') }}" class="form-inline justify-content-lg-end justify-content-start">
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
              @if (count($bookings) > 0)
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Booking ID') }}</th>
                        <th scope="col">{{ __('Event') }}</th>
                        <th scope="col">{{ __('Customer Name') }}</th>
                        <th scope="col">{{ __('Discount') }}</th>
                        <th scope="col">{{ __('Early Bird Discount') }}</th>
                        <th scope="col">{{ __('Quantity') }}</th>
                        <th scope="col">{{ __('Total') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Phone') }}</th>
                        <th scope="col">{{ __('City') }}</th>
                        <th scope="col">{{ __('State') }}</th>
                        <th scope="col">{{ __('Country') }}</th>
                        <th scope="col">{{ __('Zip Code') }}</th>
                        <th scope="col">{{ __('Gateway') }}</th>
                        <th scope="col">{{ __('Payment') }}</th>
                        <th scope="col">{{ __('Date') }}</th>
                        <th scope="col">{{ __('Receipt') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($bookings as $key => $booking)
                        <tr>
                          <td>#{{ $booking->booking_id }}</td>
                          <td><a
                              href="{{ route('event.details', ['slug' => $booking->slug, 'id' => $booking->event_id]) }}"
                              target="_blank">{{ strlen($booking->title) > 35 ? mb_substr($booking->title, 0, 35, 'utf-8') . '...' : $booking->title }}</a>
                          </td>
                          <td>{{ $booking->customerfname }} {{ $booking->customerlname }}</td>

                          <td>
                            {{ $abs->base_currency_symbol_position == 'left' ? $abs->base_currency_symbol : '' }}{{ round($booking->discount, 2) }}{{ $abs->base_currency_symbol_position == 'right' ? $abs->base_currency_symbol : '' }}
                          </td>

                          <td>
                            {{ $abs->base_currency_symbol_position == 'left' ? $abs->base_currency_symbol : '' }}{{ round($booking->early_bird_discount, 2) }}{{ $abs->base_currency_symbol_position == 'right' ? $abs->base_currency_symbol : '' }}
                          </td>

                          <td>{{ $booking->quantity }}</td>

                          <td>
                            {{ $abs->base_currency_symbol_position == 'left' ? $abs->base_currency_symbol : '' }}{{ round($booking->price, 2) }}{{ $abs->base_currency_symbol_position == 'right' ? $abs->base_currency_symbol : '' }}
                          </td>

                          <td>{{ $booking->fname }} {{ $booking->lname }}</td>
                          <td>{{ $booking->email }}</td>
                          <td>{{ $booking->phone }}</td>
                          <td>{{ $booking->city }}</td>
                          <td>{{ $booking->state }}</td>
                          <td>{{ $booking->country }}</td>
                          <td>{{ $booking->zip_code }}</td>
                          <td>{{ ucfirst($booking->paymentMethod) }}</td>
                          <td>
                            @if ($booking->paymentStatus == 'pending')
                              <span class="badge badge-warning">{{ __('Pending') }}</span>
                            @elseif ($booking->paymentStatus == 'completed')
                              <span class="badge badge-success">{{ __('Completed') }}</span>
                            @endif
                          </td>
                          <td>
                            {{ $booking->created_at }}
                          </td>
                          <td>
                            <a href="javascript:void(0)" data-toggle="modal"
                              data-target="#receiptModal{{ $booking->id }}"
                              class="btn btn-info btn-sm">{{ __('View') }}</a>
                          </td>
                        </tr>


                        {{-- Receipt Modal --}}
                        <div class="modal fade" id="receiptModal{{ $booking->id }}" tabindex="-1" role="dialog"
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
                                <iframe src="{{ asset('assets/admin/file/invoices/' . $booking->invoice) }}"
                                  class="receipt"></iframe>

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

        @if (!empty($bookings))
          <div class="card-footer">
            <div class="row">
              <div class="d-inline-block mx-auto">
                {{ $bookings->appends([
                        'from_date' => request()->input('from_date'),
                        'to_date' => request()->input('to_date'),
                        'payment_method' => request()->input('payment_method'),
                        'payment_status' => request()->input('payment_status'),
                    ])->links() }}
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>

@endsection
