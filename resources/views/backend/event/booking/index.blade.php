@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Event Booking') }}</h4>
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
        <a href="#">{{ __('Event Booking') }}</a>
      </li>
      @if (!request()->filled('status'))
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('All Bookings') }}</a>
        </li>
      @endif
      @if (request()->filled('status') && request()->input('status') == 'completed')
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Completed Bookings') }}</a>
        </li>
      @endif
      @if (request()->filled('status') && request()->input('status') == 'pending')
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Pending Bookings') }}</a>
        </li>
      @endif
      @if (request()->filled('status') && request()->input('status') == 'rejected')
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Rejected Bookings') }}</a>
        </li>
      @endif

    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-3">
              <div class="card-title">{{ __('Event Booking') }}</div>
            </div>

            <div class="col-lg-9">
              <div class="row justify-content-lg-end justify-content-start">
                <div class="col-lg-1">
                  <button class="btn btn-danger btn-sm  d-none bulk-delete ml-3 mt-1"
                    data-href="{{ route('admin.event_booking.bulk_delete') }}">
                    <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                  </button>
                </div>
                <div class="col-lg-3">
                  <form class="ml-3" action="{{ route('admin.event.booking') }}" method="GET">
                    <input name="booking_id" type="text" class="form-control"
                      placeholder="{{ __('Search By Booking Id') }}"
                      value="{{ !empty(request()->input('booking_id')) ? request()->input('booking_id') : '' }}">
                  </form>
                </div>
                <div class="col-lg-3">
                  <form class="ml-3" action="{{ route('admin.event.booking') }}" method="GET">
                    <input name="event_title" type="text" class="form-control"
                      placeholder="{{ __('Search By Event Title') }}"
                      value="{{ !empty(request()->input('event_title')) ? request()->input('event_title') : '' }}">
                  </form>
                </div>
                <div class="col-lg-3">
                  <form id="searchByStatusForm" class="d-flex flex-row align-items-center"
                    action="{{ route('admin.event.booking') }}" method="GET">
                    <label class="mr-2">{{ __('Payment') }}</label>
                    <select class="form-control" name="status"
                      onchange="document.getElementById('searchByStatusForm').submit()">
                      <option value="" {{ empty(request()->input('status')) ? 'selected' : '' }}>
                        {{ __('All') }}
                      </option>
                      <option value="completed" {{ request()->input('status') == 'completed' ? 'selected' : '' }}>
                        {{ __('Completed') }}
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
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($bookings) == 0)
                <h3 class="text-center mt-2">{{ __('NO EVENT BOOKING FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Booking ID') }}</th>
                        <th scope="col" width="25%">{{ __('Event') }}</th>
                        <th scope="col">{{ __('Organizer') }}</th>
                        <th scope="col">{{ __('Customer') }}</th>
                        <th scope="col">{{ __('Cust. Paid') }}</th>
                        <th scope="col">{{ __('Org. Received') }}</th>
                        <th scope="col">{{ __('Paid via') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Tickect Scan Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($bookings as $booking)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $booking->id }}">
                          </td>
                          <td>{{ '#' . $booking->booking_id }}</td>
                          @php
                            $eventInfo = \App\Models\Event\EventContent::where('language_id', $defaultLang->id)
                                ->where('event_id', $booking->event_id)
                                ->first();
                            if (is_null($eventInfo)) {
                                $eventInfo = \App\Models\Event\EventContent::where('event_id', $booking->event_id)->first();
                            }
                            $title = $eventInfo ? $eventInfo->title : '';
                            $slug = $eventInfo ? $eventInfo->slug : '';
                          @endphp

                          <td>
                            @if ($eventInfo)
                              <a href="{{ route('event.details', ['slug' => $slug, 'id' => $eventInfo ? $eventInfo->event_id : 0]) }}"
                                target="_blank">
                                {{ strlen($title) > 30 ? mb_substr($title, 0, 30, 'utf-8') . '...' : $title }}
                              </a>
                            @else
                              {{ '-' }}
                            @endif

                          </td>

                          <td>
                            @if ($booking->organizer)
                              <a target="_blank"
                                href="{{ route('admin.organizer_management.organizer_details', ['id' => $booking->organizer_id, 'language' => $defaultLang->code]) }}">{{ strlen($booking->organizer->username) > 20 ? mb_substr($booking->organizer->username, 0, 20, 'UTF-8') . '....' : $booking->organizer->username }}</a>
                            @else
                              <span class="badge badge-success">{{ __('Admin') }}</span>
                            @endif
                          </td>

                          @php
                            $customer = $booking->customerInfo()->first();
                          @endphp

                          <td>
                            @if ($customer)
                              <a
                                href="{{ route('admin.customer_management.customer_details', ['id' => $customer->id, 'language' => $defaultLang->code]) }}">{{ $customer->fname }}
                                {{ $customer->lname }}</a>
                            @else
                              {{ '-' }}
                            @endif
                          </td>
                          @php
                            $position = $booking->currencyTextPosition;
                            $symbol = $booking->currencySymbol;
                          @endphp

                          <td>
                            {{ $position == 'left' ? $symbol . ' ' : '' }}{{ $booking->price + $booking->tax }}{{ $position == 'right' ? ' ' . $symbol : '' }}
                          </td>
                          <td>
                            @if ($booking->organizer)
                              {{ $position == 'left' ? $symbol . ' ' : '' }}{{ $booking->price - $booking->commission }}{{ $position == 'right' ? ' ' . $symbol : '' }}
                            @else
                              -
                            @endif

                          </td>
                          <td>{{ !is_null($booking->paymentMethod) ? $booking->paymentMethod : '-' }}</td>
                          <td>
                            @if ($booking->gatewayType == 'online')
                              <h2 class="d-inline-block"><span class="badge badge-success">{{ __('Completed') }}</span>
                              </h2>
                            @elseif ($booking->gatewayType == 'offline')
                              @if ($booking->paymentStatus == 'pending')
                                <form class="d-inline-block paymentStatusForm"
                                  action="{{ route('admin.event_booking.update_payment_status', $booking->id) }}"
                                  method="post">
                                  @csrf
                                  <select
                                    class="form-control paymentStatusBtn form-control-sm @if ($booking->paymentStatus == 'completed') bg-success @elseif ($booking->paymentStatus == 'pending') bg-warning text-dark @else bg-danger @endif"
                                    name="payment_status">
                                    <option value="completed"
                                      {{ $booking->paymentStatus == 'completed' ? 'selected' : '' }}>
                                      {{ __('Completed') }}
                                    </option>
                                    <option value="pending"
                                      {{ $booking->paymentStatus == 'pending' ? 'selected' : '' }}>
                                      {{ __('Pending') }}
                                    </option>
                                    <option value="rejected"
                                      {{ $booking->paymentStatus == 'rejected' ? 'selected' : '' }}>
                                      {{ __('Rejected') }}
                                    </option>
                                  </select>
                                </form>
                              @else
                                <span
                                  class="badge badge-{{ $booking->paymentStatus == 'rejected' ? 'danger' : 'success' }}">{{ ucfirst($booking->paymentStatus) }}</span>
                              @endif
                            @else
                              @if ($booking->paymentStatus == 'free')
                                <span class="badge badge-primary">{{ ucfirst($booking->paymentStatus) }}</span>
                              @else
                                -
                              @endif
                            @endif
                          </td>
                          <td>
                            @if ($booking->scan_status == 1)
                              <span class="badge badge-success">{{ __('Already Scanned') }}</span>
                            @else
                              <span class="badge badge-danger">{{ __('Not Scanned') }}</span>
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
                                <a href="{{ route('admin.event_booking.details', ['id' => $booking->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>

                                @if (!is_null($booking->attachmentFile))
                                  <a href="#" data-toggle="modal"
                                    data-target="#attachmentModal-{{ $booking->id }}" class="dropdown-item"
                                    target="_blank">
                                    {{ __('Attachment') }}
                                  </a>
                                @endif
                                @php
                                  $exts = explode('.', $booking->invoice);
                                  $info = new SplFileInfo($booking->invoice);
                                  $ext = $info->getExtension();
                                @endphp
                                @if ($ext == 'pdf')
                                  <a href="{{ asset('assets/admin/file/invoices/' . $booking->invoice) }}"
                                    class="dropdown-item" target="_blank">
                                    {{ __('Invoice') }}
                                  </a>
                                @endif


                                <form class="deleteForm d-block"
                                  action="{{ route('admin.event_booking.delete', ['id' => $booking->id]) }}"
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

                        @includeIf('backend.event.booking.show-attachment')
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
            {{ $bookings->appends([
                    'booking_id' => request()->input('booking_id'),
                    'status' => request()->input('status'),
                ])->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
