@extends('frontend.layout')
@section('pageHeading')
  {{ __('Check Out') }}
@endsection
@section('custom-style')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-content.css') }}">
@endsection
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">{{ __('Checkout') }}</h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Checkout') }}</li>
          </ol>
        </nav>
      </div>
    </div>
  </section>
  <!-- Page Banner End -->
@endsection
@section('content')
  <!-- CheckOut Area Start -->
  <section class="checkout-area pt-120 rpt-95 pb-90 rpb-70">
    <div class="container">
      <form class="form" action="{{ route('ticket.booking', $event->id) }}" method="POST" enctype="multipart/form-data"
        id="payment-form">
        @csrf
        <div class="row">
          <div class="col-lg-8">
            <h3 class="from-title mb-25">{{ __('Billing Details') }}</h3>
            <hr>
            <div class="row mt-35">
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="fname">{{ __('First Name') }} *</label>
                  <input type="text" name="fname" value="{{ old('fname', Auth::guard('customer')->user()->fname) }}"
                    id="fname" class="form-control" placeholder="{{ __('Enter Your First Name') }}">
                  @error('fname')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="lname">{{ __('Last Name') }} *</label>
                  <input type="text" name="lname" value="{{ old('lname', Auth::guard('customer')->user()->lname) }}"
                    id="lname" class="form-control" placeholder="{{ __('Enter Your Last Name') }}">
                  @error('lname')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="email">{{ __('Email') }} *</label>
                  <input type="text" name="email" id="email"
                    value="{{ old('email', Auth::guard('customer')->user()->email) }}" class="form-control"
                    placeholder="{{ __('Enter Your Email') }}">
                  @error('email')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="address">{{ __('Phone') }} *</label>
                  <input type="text" name="phone" id="phone" class="form-control"
                    value="{{ old('phone', Auth::guard('customer')->user()->phone) }}"
                    placeholder="{{ __('Phone Number') }}">
                  @error('phone')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="state">{{ __('Country') }} *</label>
                  <input type="text" name="country"
                    value="{{ old('country', Auth::guard('customer')->user()->country) }}" class="form-control"
                    placeholder="{{ __('Country') }}">
                  @error('country')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="state">{{ __('State') }}</label>
                  <input type="text" name="state" value="{{ old('state', Auth::guard('customer')->user()->state) }}"
                    class="form-control" placeholder="{{ __('State') }}">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="city">{{ __('City') }} *</label>
                  <input type="text" name="city" value="{{ old('city', Auth::guard('customer')->user()->city) }}"
                    class="form-control" placeholder="{{ __('City') }}">
                  @error('city')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="company">{{ __('Zip/Post Code') }}</label>
                  <input type="text" name="zip_code"
                    value="{{ old('zip_code', Auth::guard('customer')->user()->zip_code) }}" class="form-control"
                    placeholder="{{ __('Zip/Post Code') }}">

                </div>
              </div>
              <div class="col-sm-12">
                <label for="">{{ __('Address') }} * </label>
                <textarea name="address" class="form_control" cols="2" rows="3" placeholder="{{ __('Address') }}">{{ old('address', Auth::guard('customer')->user()->address) }}</textarea>
                @error('address')
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <input type="hidden" name="event" value="{{ $event }}">
            <input type="hidden" name="total" value="{{ $total }}">
            <input type="hidden" name="quantity" value="{{ $quantity }}">
            @if ($selTickets != '')
              @php
                Session::put('selTickets', $selTickets);
              @endphp
            @endif
            @csrf
            <div class="checkout-product mb-25">
              <a href="{{ route('event.details', [$event->slug, $event->id]) }}" class="checkout-product-img">
                <img src="{{ asset('assets/admin/img/event/thumbnail/' . $event->thumbnail) }}" alt="Checkout">
              </a>
              <div class="content">
                <h6><a href="{{ route('event.details', [$event->slug, $event->id]) }}">{{ @$event->title }}</a></h6>
                <span> <i class="fas fa-calendar-alt"></i> {{ date('D, d M Y', strtotime($event->start_date)) }} &nbsp;
                  <i class="fas fa-clock"></i> {{ $event->start_time }}</span>
                @if ($event->event_type == 'venue')
                  <span>
                    <i class="fas fa-map-marker-alt"></i>
                    @if ($event->city != null)
                      {{ $event->city }}
                    @endif
                    @if ($event->country)
                      , {{ $event->country }}
                    @endif
                  </span>
                @else
                  <a href="#">{{ __('Online') }}</a>
                @endif
              </div>
            </div>
            <h3 class="from-title mb-25">{{ __('Order Summary') }}</h3>
            <div>
              <div id="couponReload">
                @php
                  $selTickets = Session::get('selTickets');
                @endphp
                <ul class="package-summary mb-25">
                  @if ($selTickets != null)
                    <li>
                      <span class="text"><strong>{{ __('Tickets Info') }}</strong></span>
                    </li>
                    @foreach ($selTickets as $selTicket)
                      @php
                        $ticket = App\Models\Event\Ticket::where('id', $selTicket['ticket_id'])->first();
                        
                        if ($ticket->pricing_type == 'variation') {
                            $varition_key = App\Models\Event\VariationContent::where([['ticket_id', $selTicket['ticket_id']], ['name', $selTicket['name']]])
                                ->select('key')
                                ->first();
                        
                            $varition_name = App\Models\Event\VariationContent::where([['ticket_id', $ticket->id], ['language_id', $currentLanguageInfo->id], ['key', $varition_key->key]])->first();
                        
                            if ($varition_name) {
                                $name = $varition_name->name;
                            } else {
                                $name = '';
                            }
                        } else {
                            $ticket_content = App\Models\Event\TicketContent::where([['ticket_id', $ticket->id], ['language_id', $currentLanguageInfo->id]])->first();
                            if (empty($ticket_content)) {
                                $ticket_content = App\Models\Event\TicketContent::where([['ticket_id', $ticket->id]])->first();
                            }
                            $name = $ticket_content->title;
                        }
                      @endphp
                      <li>
                        <span class="text">{{ $name }}</span>
                        <span class="number">{{ $selTicket['qty'] }}x</span>
                      </li>
                    @endforeach

                    <hr>
                  @endif
                  <li><span class="text">{{ __('Total Tickets') }}</span> <span
                      class="number">{{ $quantity }}</span></li>
                  <li><span class="text">{{ __('Ticket Price') }}</span>
                    <span dir="ltr" class="number">
                      @if (Session::get('total_early_bird_dicount') != '')
                        {{ symbolPrice(Session::get('sub_total') - Session::get('total_early_bird_dicount')) }}
                      @else
                        {{ symbolPrice(Session::get('sub_total')) }}
                      @endif
                      @if (Session::get('total_early_bird_dicount') != 0)
                        <del class="number">
                          {{ symbolPrice(Session::get('sub_total')) }}
                        </del>
                      @endif
                    </span>
                  </li>

                  @if (Session::get('discount') != '')
                    <li><span class="text">{{ __('Coupon Discount') }}</span> <span class="number" dir="ltr">
                        <span class="text-success"><strong>-</strong>
                          {{ symbolPrice(Session::get('discount')) }}
                        </span>
                      </span>
                    </li>
                  @endif


                  @if (Session::get('total_early_bird_dicount') != '')
                    <li><span class="text">{{ __('Subtotal') }}</span> <span class="number" dir="ltr">

                        @if (Session::get('total_early_bird_dicount') != '')
                          @php
                            $symbol_subtotal = Session::get('sub_total') - (Session::get('total_early_bird_dicount') + Session::get('discount'));
                          @endphp
                          {{ symbolPrice($symbol_subtotal) }}
                        @else
                          {{ symbolPrice(Session::get('sub_total') - Session::get('discount')) }}
                        @endif
                    </li>
                  @endif

                  @php
                    if (Session::get('total_early_bird_dicount') != '') {
                        $subtotal = Session::get('sub_total') - (Session::get('total_early_bird_dicount') + Session::get('discount'));
                    } else {
                        $subtotal = Session::get('sub_total') - Session::get('discount');
                    }
                    $tax = ($subtotal * $basicData->tax) / 100;
                    $tax = round($tax, 2);
                  @endphp
                  <li><span class="text">{{ __('Tax') }} (<span
                        dir="ltr">{{ $basicData->tax }}%</span>)</span> <span class="number" dir="ltr">
                      <span class="text-danger">
                        <strong>+</strong>
                        {{ symbolPrice($tax) }}
                      </span>
                    </span>
                  </li>
                  <li><span class="text">{{ __('Total') }}</span> <span class="number" dir="ltr">
                      @php
                        $symbol_total = Session::get('sub_total') - (Session::get('discount') + Session::get('total_early_bird_dicount')) + $tax;
                      @endphp
                      {{ symbolPrice($symbol_total) }}
                    </span>
                  </li>
                  @php
                    $sub_total = Session::get('sub_total');
                    $discount = Session::get('discount');
                    $total_early_bird_dicount = Session::get('total_early_bird_dicount');
                    
                    $grand_total = $sub_total + $tax - ($discount + $total_early_bird_dicount);
                    Session::put('tax', $tax);
                    Session::put('grand_total', $sub_total - ($discount + $total_early_bird_dicount));
                  @endphp
                </ul>
              </div>
            </div>

            @if ($total != 0 || Session::get('sub_total') != 0)
              <div class="coupon">
                <h4 class="mb-3">{{ __('Coupon') }}</h4>
                <div class="input-group d-flex">
                  <input type="text" onsubmit="event.preventDefault();" class="form-control" name="coupon"
                    id="coupon-code" value="">
                  <div class="input-group-append">
                    <button class="btn theme-btn base-btn" type="button">{{ __('Apply') }}</button>
                  </div>
                </div>
              </div>
              <h5 class="from-title mt-20 mb-15">{{ __('Payment Method') }}</h5>
              @if (Session::has('paypal_error'))
                <p class="text-danger">{{ Session::get('paypal_error') }}</p>
                @php
                  Session::forget('paypal_error');
                @endphp
              @endif
              @if (Session::has('error'))
                <p class="text-danger">{{ Session::get('error') }}</p>
              @endif
              <div class="form-group">
                <select name="gateway" id="payment">
                  <option value="">{{ __('Select a payment method') }}</option>
                  @foreach ($online_gateways as $online_gateway)
                    <option value="{{ $online_gateway->keyword }}"
                      {{ $online_gateway->keyword == old('gateway') ? 'selected' : '' }}>
                      {{ __("$online_gateway->name") }}</option>
                  @endforeach
                  @foreach ($offline_gateways as $offline_gateway)
                    <option value="{{ $offline_gateway->id }}"
                      {{ $offline_gateway->id == old('gateway') ? 'selected' : '' }}>
                      {{ __("$offline_gateway->name") }}</option>
                  @endforeach
                </select>
                @error('gateway')
                  <p class="text-danger">{{ $message }}</p>
                @enderror()
                @if (Session::has('currency_error'))
                  <p class="text-danger">{{ Session::get('currency_error') }}</p>
                @endif
              </div>
              <div id="stripe-element" class="mb-2">
                <!-- A Stripe Element will be inserted here. -->
              </div>
              <!-- Used to display form errors -->
              <div id="stripe-errors" role="alert" class="mb-2"></div>
              @foreach ($offline_gateways as $offlineGateway)
                <div class="@if (
                    $errors->has('attachment') &&
                        request()->session()->get('gatewayId') == $offlineGateway->id) d-block @else d-none @endif offline-gateway-info"
                  id="{{ 'offline-gateway-' . $offlineGateway->id }}">
                  @if (!is_null($offlineGateway->short_description))
                    <div class="form-group mb-4">
                      <label>{{ __('Description') }}</label>
                      <p>{{ $offlineGateway->short_description }}</p>
                    </div>
                  @endif

                  @if (!is_null($offlineGateway->instructions))
                    <div class="form-group mb-4">
                      <label>{{ __('Instructions') }}</label>
                      <div class="summernote-content">
                        {!! $offlineGateway->instructions !!}
                      </div>
                    </div>
                  @endif

                  @if ($offlineGateway->has_attachment == 1)
                    <div class="form-group mb-4">
                      <label>{{ __('Attachment') . '*' }}</label>
                      <br>
                      <input type="file" name="attachment">
                      @error('attachment')
                        <p class="text-danger mt-1">{{ $message }}</p>
                      @enderror
                      <p></p>
                    </div>
                  @endif
                </div>
              @endforeach

              <button type="submit" class="theme-btn w-100">{{ __('Proceed to Pay') }}</button>
            @else
              <button type="submit" class="theme-btn w-100">{{ __('Submit') }}</button>
            @endif


          </div>
        </div>
      </form>
    </div>
  </section>
  <!-- CheckOut Area End -->
@endsection

@section('custom-script')
  <script src="https://js.stripe.com/v3/"></script>
  <script type="text/javascript">
    let url = "{{ route('apply-coupon') }}";
    let stripe_key = "{{ $stripe_key }}";
  </script>
  <script src="{{ asset('assets/front/js/event_checkout.js') }}"></script>
@endsection
