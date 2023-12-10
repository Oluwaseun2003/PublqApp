<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

  <link rel="stylesheet" href="{{ asset('assets/admin/css/newinvoice.css') }}">
</head>
<body>
        @php
          $position = $bookingInfo->currencyTextPosition;
          $currency = $bookingInfo->currencyText;
        @endphp
    <section class="invoice-sec">
        <div class="img-div">

        </div>
<!-- 


@if (@$bookingInfo->evnt->date_type == 'single')
                <p>
                  {{ __('Event End Date') . ': ' }} <span class="text-muted">
                    {{ Carbon\Carbon::parse(@$bookingInfo->evnt->end_date . @$bookingInfo->evnt->end_time)->format('d M, Y h:ia') }}
                  </span>
                </p>
                <p>
                  {{ __('Duration') . ': ' }} <span class="text-muted">
                    {{ @$bookingInfo->evnt->duration }}
                  </span>
                </p>
              @else
                @php
                  $date = Carbon\Carbon::parse($bookingInfo->event_date)->format('Y-m-d');
                  $time = Carbon\Carbon::parse($bookingInfo->event_date)->format('H:i');
                  $evnt = @$bookingInfo->evnt
                      ->dates()
                      ->where('start_date', $date)
                      ->where('start_time', $time)
                      ->first();
                @endphp

                <p>
                  {{ __('Event End Date') . ': ' }} <span class="text-muted">
                    @if (!empty($evnt))
                      {{ Carbon\Carbon::parse(@$evnt->end_date . @$evnt->end_time)->format('D, M d, Y H:i a') }}
                    @endif
                  </span>
                </p>

                <p>
                  {{ __('Duration') . ': ' }} <span class="text-muted">
                    @if (!empty($evnt))
                      {{ $evnt->duration }}
                    @endif
                  </span>
                </p>
              @endif


 -->


        <div class="text-div">
            <div>
            <h3>Booking Details</h3>

            <p class="head-txt">Event Name: <span>{{ @$eventInfo->title }}</span></p>
            <p class="head-txt">Booking date: <span>{{ date_format($bookingInfo->created_at, 'M d, Y') }}</span></p>
            <p class="head-txt">Event Start Date: <span>{{ FullDateTimeInvoice($bookingInfo->event_date) }}</span></p>
            
            @if (@$bookingInfo->evnt->date_type == 'single')
                <p class="head-txt">Event End Date: 
                    <span>
                        {{ Carbon\Carbon::parse(@$bookingInfo->evnt->end_date . @$bookingInfo->evnt->end_time)->format('d M, Y h:ia') }}
                    </span>
                </p>
            @else
            @php
                  $date = Carbon\Carbon::parse($bookingInfo->event_date)->format('Y-m-d');
                  $time = Carbon\Carbon::parse($bookingInfo->event_date)->format('H:i');
                  $evnt = @$bookingInfo->evnt
                      ->dates()
                      ->where('start_date', $date)
                      ->where('start_time', $time)
                      ->first();
                @endphp
            <p class="head-txt">Event End Date: 
                <span>
                @if (!empty($evnt))
                    {{ Carbon\Carbon::parse(@$evnt->end_date . @$evnt->end_time)->format('D, M d, Y H:i a') }}
                @endif
                </span>
            </p>
            @endif
            </div>

            <div class="id-div">
            <p class="head-txt">Booking ID:  <span>{{ '#' . $bookingInfo->booking_id }}</span></p>
            <p class="head-txt">Quantity: <span>1</span></p>

            @if (@$bookingInfo->evnt->date_type == 'single')
            <p class="head-txt">Duration: <span>{{ @$bookingInfo->evnt->duration }}</span></p>
            @else
                @php
                  $date = Carbon\Carbon::parse($bookingInfo->event_date)->format('Y-m-d');
                  $time = Carbon\Carbon::parse($bookingInfo->event_date)->format('H:i');
                  $evnt = @$bookingInfo->evnt
                      ->dates()
                      ->where('start_date', $date)
                      ->where('start_time', $time)
                      ->first();
                @endphp

                <p class="head-txt">Duration: <span>
                @if (!empty($evnt))
                      {{ $evnt->duration }}
                    @endif
                </span>
                </p>


            @if (@$bookingInfo->evnt->date_type == 'single')
                <p class="head-txt">Event End Date: <span>{{ Carbon\Carbon::parse(@$bookingInfo->evnt->end_date . @$bookingInfo->evnt->end_time)->format('d M, Y h:ia') }}</span></p>
            
            @else
            @php
                  $date = Carbon\Carbon::parse($bookingInfo->event_date)->format('Y-m-d');
                  $time = Carbon\Carbon::parse($bookingInfo->event_date)->format('H:i');
                  $evnt = @$bookingInfo->evnt
                      ->dates()
                      ->where('start_date', $date)
                      ->where('start_time', $time)
                      ->first();
                @endphp
            <p class="head-txt">Event End Date: 
                <span>
                @if (!empty($evnt))
                    {{ Carbon\Carbon::parse(@$evnt->end_date . @$evnt->end_time)->format('D, M d, Y H:i a') }}
                @endif
                </span>
            </p>
            @endif
            @endif
            </div>

           
        </div>

        <div class="qr-div">
            <div class="qr-img">
                <img src="{{ asset('assets/admin/img/qrcode.png') }}" alt="">

            </div>

            <div class="qr-txt">
            <p class="head-txt">
                Tax({{ $bookingInfo->tax_percentage }}%): 
                <span>
                    {{ $position == 'left' ? $currency . ' ' : '' }}{{ is_null($bookingInfo->tax) ? '0.00' : $bookingInfo->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}
                </span>
            </p>
            <p class="head-txt">Early Bird Discount: 
                <span>
                {{ $position == 'left' ? $currency . ' ' : '' }}{{ is_null($bookingInfo->early_bird_discount) ? '0.00' : $bookingInfo->early_bird_discount }}{{ $position == 'right' ? ' ' . $currency : '' }}
                </span>
            </p>
            <p class="head-txt">Total Paid:<span>{{ $position == 'left' ? $currency . ' ' : '' }}{{ $bookingInfo->price + $bookingInfo->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}</span></p>
            <p class="head-txt">Payment Method:<span>{{ is_null($bookingInfo->paymentMethod) ? '-' : $bookingInfo->paymentMethod }}</span></p>
            <p class="head-txt">Payment Status:
                <span>
                @if ($bookingInfo->paymentStatus == 'completed')
                    {{ __('Completed') }}
                  @elseif ($bookingInfo->paymentStatus == 'pending')
                    {{ __('Pending') }}
                  @elseif ($bookingInfo->paymentStatus == 'rejected')
                    {{ __('Rejected') }}
                  @else
                    -
                  @endif
                </span>
            </p>

            </div>
    
    </section>
   

    
</body>

<footer>
    <div class="bill">
        <h3>Biling Details</h3>

        <p class="head-txt">Name:<span>Nov 21, 2023</span></p>
                <p class="head-txt">Email: <span>1</span></p>
                <p class="head-txt">Contact Number: <span>1hr</span></p>

    </div>
        <div>
            <img class="qr-img" src="{{ asset('assets/admin/img/logo.png') }}" alt="">
        </div>
</footer>
</html>