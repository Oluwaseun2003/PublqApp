@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->customer_booking_details_page_title ?? __('Booking Details') }}
  @else
    {{ __('Booking Details') }}
  @endif
@endsection
@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">
          @if (!empty($pageHeading))
            {{ $pageHeading->customer_booking_details_page_title ?? __('Booking Details') }}
          @else
            {{ __('Booking Details') }}
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
                {{ $pageHeading->customer_booking_details_page_title ?? __('Booking Details') }}
              @else
                {{ __('Booking Details') }}
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
                <div class="order-details">

                  <div class="title">
                    <div class="row">
                      <div class="col-lg-8">
                        <h4>{{ __('Booking details') }}</h4>
                      </div>
                      <div class="col-lg-4">
                        <a href="{{ route('customer.booking.my_booking') }}" class="btn float-lg-right float-left">
                          {{ __('back') }}</a>
                      </div>
                    </div>
                  </div>
                  <div class="view-order-page">
                    <div class="order-info-area">
                      <div class="row align-items-center">
                        <div class="col-lg-9">
                          <div class="order-info">
                            <h3>{{ __('Booking') . ' #' }} {{ $booking->booking_id }}
                              [{{ __($booking->paymentStatus) }}]
                            </h3>
                            <p><b>{{ __('Booking Date') . ' : ' }}</b>
                              {{ \Carbon\Carbon::parse($booking->created_at)->translatedFormat('D, M d, Y h:i a') }}
                            </p>
                            <p><b>{{ __('Event Start Date') . ' : ' }}</b>
                              {{ \Carbon\Carbon::parse($booking->event_date)->translatedFormat('D, M d, Y h:i a') }}
                            </p>
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="prinit">
                            @php
                              $exts = explode('.', $booking->invoice);
                              $info = new SplFileInfo($booking->invoice);
                              $ext = $info->getExtension();
                            @endphp
                            @if ($ext == 'pdf')
                              <a href="{{ asset('assets/admin/file/invoices/' . $booking->invoice) }}" download
                                class="btn"><i class="fas fa-download"></i>{{ __('Download') }}</a>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="billing-add-area">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="main-info">
                          <h5>{{ __('Billing Details') }}</h5>
                          <ul class="list">
                            <li>
                              <p><b>{{ __('Name') . ' : ' }}</b>{{ $booking->fname }}
                                {{ $booking->lname }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Email') . ' : ' }}</b>{{ $booking->email }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Phone') . ' : ' }} </b>{{ $booking->phone }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Country') . ' : ' }} </b>{{ $booking->country }}
                              </p>
                            </li>
                            <li>
                              <p><b>{{ __('State') . ' : ' }} </b>{{ $booking->state }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('City') . ' : ' }} </b>{{ $booking->city }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Zip-code') . ' : ' }}
                                </b>{{ $booking->zip_code }}</p>
                            </li>
                            <li>
                              <p><b>{{ __('Address') . ' : ' }} </b> {{ $booking->address }}
                              </p>
                            </li>
                          </ul>
                        </div>
                      </div>
                      @php
                        $position = $booking->currencyTextPosition;
                        $currency = $booking->currencyText;
                      @endphp
                      <div class="col-md-4">
                        <div class="payment-information">
                          <h5>{{ __('Payment Info') }}</h5>

                          <p><b>{{ __('Event') . ' : ' }} </b> <span>
                              @php
                                $event = $booking
                                    ->event()
                                    ->where('language_id', $currentLanguageInfo->id)
                                    ->select('title', 'slug', 'event_id')
                                    ->first();
                                if (empty($event)) {
                                    $language = App\Models\Language::where('is_default', 1)->first();
                                    $event = $booking
                                        ->event()
                                        ->where('language_id', $language->id)
                                        ->select('title', 'slug', 'event_id')
                                        ->first();
                                }
                              @endphp
                              <a
                                href="{{ route('event.details', ['slug' => $event->slug, 'id' => $event->event_id]) }}">{{ strlen($event->title) > 30 ? mb_substr($event->title, 0, 30) . '...' : $event->title }}</a>
                            </span></p>

                          @if ($booking->early_bird_discount != null)
                            <p><b>{{ __('Total Early Bird Discount') . ' : ' }} </b> <span
                                class="amount">{{ $booking->currencySymbol }}
                                {{ $booking->early_bird_discount }}</span></p>
                          @endif
                          @if ($booking->discount != null)
                            <p><b>{{ __('Coupon Discount') . ' : ' }} </b> <span
                                class="amount">{{ $booking->currencySymbol }}
                                {{ $booking->discount }}</span></p>
                          @endif
                          @if (!is_null($booking->tax))
                            <p><b>{{ __('Tax') . ' : ' }} </b>
                              <span dir="ltr">
                                {{ $position == 'left' ? $currency . ' ' : '' }}{{ $booking->tax }}{{ $position == 'right' ? ' ' . $currency : '' }}
                              </span>
                            </p>
                          @endif

                          <p><b>{{ __('Total Paid') . ' : ' }} </b> <span class="amount"
                              dir="ltr">{{ $booking->currencySymbol }}{{ $booking->price + $booking->tax }}
                            </span></p>

                          <p><b>{{ __('Payment Status') . ' : ' }} </b> <span
                              class="badge {{ $booking->paymentStatus == 'completed' ? 'badge-success' : 'badge-danger' }} ">{{ __($booking->paymentStatus) }}</span>
                          </p>
                          <p><b>{{ __('Payment Method') . ' : ' }}</b>
                            {{ __($booking->paymentMethod) }}</p>
                          @if (is_null($booking->variation))
                            <p><b>{{ __('Quantity') . ' : ' }}</b>
                              {{ __($booking->quantity) }}</p>
                          @endif
                        </div>
                      </div>
                      @if ($booking->organizer)
                        <div class="col-md-4">
                          <div class="payment-information">
                            <h5>{{ __('Organizer') }}</h5>

                            <p><b>{{ __('Username') . ' : ' }}</b> <span>
                                <a target="_blank"
                                  href="{{ route('frontend.organizer.details', [$booking->organizer->id, str_replace(' ', '-', $booking->organizer->username)]) }}">{{ $booking->organizer->username }}</a>
                            </p>

                            <p><b>{{ __('Email') . ' : ' }}</b>
                              {{ @$booking->organizer->email }}</p>
                            <p><b>{{ __('Phone') . ' : ' }}</b>
                              {{ @$booking->organizer->phone }}</p>
                            <p><b>{{ __('City') . ' : ' }}</b>
                              {{ @$booking->organizer->organizer_info->city }}</p>
                            <p><b>{{ __('State') . ' : ' }}</b>
                              {{ @$booking->organizer->organizer_info->state }}</p>
                            <p><b>{{ __('Country') . ' : ' }}</b>
                              {{ @$booking->organizer->organizer_info->country }}
                            </p>
                            <p><b>{{ __('Address') . ' : ' }}</b>
                              {{ @$booking->organizer->organizer_info->address }}
                            </p>

                          </div>
                        </div>
                      @endif
                    </div>
                  </div>

                  @if ($booking->variation != null)
                    <div class="table-responsive product-list">
                      <h5>{{ __('Booked Tickets') }}</h5>
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>{{ __('Ticket') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Price') }}</th>
                          </tr>
                        </thead>
                        <tbody>

                          @php
                            $variations = json_decode($booking->variation, true);
                          @endphp
                          @foreach ($variations as $variation)
                            <tr>
                              <td>
                                @php
                                  $ticket = App\Models\Event\Ticket::where('id', $variation['ticket_id'])->first();
                                  
                                  $ticketContent = App\Models\Event\TicketContent::where([['ticket_id', $ticket->id], ['language_id', $currentLanguageInfo->id]])->first();
                                @endphp
                                @if ($ticketContent && $ticket)
                                  {{ $ticketContent->title }}
                                  @if ($ticket->pricing_type == 'variation')
                                    @php
                                      $varition_key = App\Models\Event\VariationContent::where([['ticket_id', $ticket->id], ['name', $variation['name']]])
                                          ->select('key')
                                          ->first();
                                      
                                      $varition_name = App\Models\Event\VariationContent::where([['ticket_id', $ticket->id], ['language_id', $currentLanguageInfo->id], ['key', $varition_key->key]])->first();
                                      
                                      if ($varition_name) {
                                          $name = $varition_name->name;
                                      } else {
                                          $name = '';
                                      }
                                    @endphp
                                    <small>({{ $name }})</small>
                                  @endif
                                @endif
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
                        </tbody>
                      </table>
                    </div>
                  @endif
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
@section('script')
  <script src="{{ asset('assets/front/js/page.js') }}"></script>
@endsection
