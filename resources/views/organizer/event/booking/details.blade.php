@extends('organizer.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Booking Details') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('organizer.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Event Booking') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      @php
        $eventInfos = \App\Models\Event\EventContent::where('language_id', $defaultLang->id)
            ->where('event_id', $booking->event_id)
            ->select('slug', 'event_id', 'title')
            ->first();
        if (empty($eventInfos)) {
            $eventInfos = \App\Models\Event\EventContent::where('event_id', $booking->event_id)
                ->select('slug', 'event_id', 'title')
                ->first();
        }
        
        $slug = $eventInfos ? $eventInfos->slug : '';
      @endphp
      <li class="nav-item">
        <a href="#">{{ $eventInfos->title }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Booking Details') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    @php
      $position = $booking->currencyTextPosition;
      $currency = $booking->currencyText;
    @endphp

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">
                {{ __('Booking ID') . ' ' . '#' . $booking->booking_id }}
              </div>
            </div>
            <div class="col-lg-4">
              <a class="btn btn-info btn-sm float-right d-inline-block mr-2" href="{{ url()->previous() }}">
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
                <strong>{{ __('Event') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if (!empty($eventInfos))
                  <a href="{{ route('event.details', ['slug' => $slug, 'id' => $eventInfos ? $eventInfos->event_id : 0]) }}"
                    target="_blank">
                    {{ strlen($eventInfos->title) > 30 ? mb_substr($eventInfos->title, 0, 30, 'utf-8') . '...' : $eventInfos->title }}
                  </a>
                @endif
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <strong>{{ __('Booking Date') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ FullDateTime($booking->created_at) }}
              </div>
            </div>

            <div class="row">
              <div class="col-lg-4">
                <strong>{{ __('Event Start Date') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $booking->event_date }}
              </div>
            </div>

            @if (@$booking->evnt->date_type == 'single')
              <div class="row">
                <div class="col-lg-4">
                  <strong>{{ __('Event End Date') . ' :' }}</strong>
                </div>
                <div class="col-lg-8">
                  {{ Carbon\Carbon::parse(@$booking->evnt->end_date . @$booking->evnt->end_time)->format('D, M d, Y h:i A') }}
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <strong>{{ __('Duration') . ' :' }}</strong>
                </div>
                <div class="col-lg-8">
                  {{ @$booking->evnt->duration }}
                </div>
              </div>
            @else
              @php
                $date = Carbon\Carbon::parse($booking->event_date)->format('Y-m-d');
                $time = Carbon\Carbon::parse($booking->event_date)->format('H:i');
                $evnt = @$booking->evnt
                    ->dates()
                    ->where('start_date', $date)
                    ->where('start_time', $time)
                    ->first();
              @endphp
              <div class="row">
                <div class="col-lg-4">
                  <strong>{{ __('Event End Date') . ' :' }}</strong>
                </div>
                <div class="col-lg-8">
                  @if (!empty($evnt))
                    {{ Carbon\Carbon::parse(@$evnt->end_date . @$evnt->end_time)->translatedFormat('D, M d, Y h:i a') }}
                  @endif
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4">
                  <strong>{{ __('Duration') . ' :' }}</strong>
                </div>
                <div class="col-lg-8">
                  @if (!empty($evnt))
                    {{ $evnt->duration }}
                  @endif
                </div>
              </div>
            @endif

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Early Bird Discount') }} <span class="text-success">(-)</span>
                  :</strong></strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($booking->early_bird_discount))
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $booking->early_bird_discount }}{{ $position == 'right' ? ' ' . $currency : '' }}
                @else
                  -
                @endif
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Coupon Discount') }} <span class="text-success">(-)</span> :</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($booking->discount))
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $booking->discount }}{{ $position == 'right' ? ' ' . $currency : '' }}
                @else
                  -
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Tax') }} <span class="text-danger">({{ $booking->tax_percentage }}%+)</span>
                </strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($booking->tax))
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $booking->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}
                  ({{ __('Received by Admin') }})
                @else
                  -
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Customer Paid') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($booking->price))
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $booking->price + $booking->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}
                @else
                  -
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Commission') }} ({{ $booking->commission_percentage }}%)</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($booking->commission))
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $booking->commission }}{{ $position == 'right' ? ' ' . $currency : '' }}
                  ({{ __('Received by Admin') }})
                @else
                  -
                @endif
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Received by Organization') }} </strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($booking->price - $booking->commission))
                  {{ $position == 'left' ? $currency . ' ' : '' }}{{ $booking->price - $booking->commission }}{{ $position == 'right' ? ' ' . $currency : '' }}
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
                @if (!is_null($booking->paymentMethod))
                  {{ $booking->paymentMethod }}
                @else
                  -
                @endif
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Quantity') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($booking->quantity))
                  {{ $booking->quantity }}
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
                @if ($booking->paymentStatus == 'completed')
                  <span class="badge badge-success">{{ __('Completed') }}</span>
                @elseif ($booking->paymentStatus == 'pending')
                  <span class="badge badge-warning">{{ __('Pending') }}</span>
                @elseif ($booking->paymentStatus == 'rejected')
                  <span class="badge badge-danger">{{ __('Rejected') }}</span>
                @else
                  -
                @endif
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Tickect Scan Status') }}</strong>
              </div>
              <div class="col-lg-8">
                @if ($booking->scan_status == 1)
                  <span class="badge badge-success">{{ __('Already Scanned') }}</span>
                @else
                  <span class="badge badge-danger">{{ __('Not Scanned') }}</span>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
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
                {{ $booking->fname . ' ' . $booking->lname }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Email') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $booking->email }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Phone') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $booking->phone }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('Address') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $booking->address }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('City') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                {{ $booking->city }}
              </div>
            </div>

            <div class="row mb-2">
              <div class="col-lg-4">
                <strong>{{ __('State') . ' :' }}</strong>
              </div>
              <div class="col-lg-8">
                @if (!is_null($booking->state))
                  {{ $booking->state }}
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
                {{ $booking->country }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @if ($booking->variation != null)
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <div class="card-title d-inline-block">
              {{ __('Tickets Info') }}
            </div>
          </div>

          <div class="card-body">
            <div class="payment-information">
              <table class="table">
                <tr>
                  <th>{{ __('Ticket') }}</th>
                  <th>{{ __('Quantity') }}</th>
                  <th>{{ __('Price') }}</th>
                </tr>
                @if ($booking->variation != null)
                  @php
                    $variations = json_decode($booking->variation, true);
                  @endphp
                  @foreach ($variations as $variation)
                    <tr>
                      <td>
                        @php
                          $ticket_content = App\Models\Event\TicketContent::where([['ticket_id', $variation['ticket_id']], ['language_id', $defaultLang->id]])->first();
                          
                          $ticket = App\Models\Event\Ticket::where('id', $variation['ticket_id'])
                              ->select('pricing_type')
                              ->first();
                        @endphp
                        @if ($ticket_content && $ticket->pricing_type == 'variation')
                          {{ $ticket_content->title }} -
                        @endif
                        <small>{{ $variation['name'] }}</small>
                      </td>
                      <td>
                        {{ $variation['qty'] }}
                      </td>
                      <td>
                        @php
                          $evd = $variation['early_bird_dicount'] / $variation['qty'];
                        @endphp
                        {{ symbolPrice($variation['price'] - $evd) }}
                        @if ($variation['early_bird_dicount'] != null)
                          <del>{{ symbolPrice($variation['price']) }}</del>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                @endif
              </table>
            </div>
          </div>
        </div>
      </div>
    @endif

  </div>
@endsection
