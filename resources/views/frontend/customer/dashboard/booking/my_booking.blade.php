@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->customer_booking_page_title ?? __('Event Bookings') }}
  @else
    {{ __('Event Bookings') }}
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
            {{ $pageHeading->customer_booking_page_title ?? __('Event Bookings') }}
          @else
            {{ __('Event Bookings') }}
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
                {{ $pageHeading->customer_booking_page_title ?? __('Event Bookings') }}
              @else
                {{ __('Event Bookings') }}
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
                    <h4>{{ __('Event Bookings') }}</h4>
                  </div>
                  <div class="main-info">
                    <div class="main-table">
                      <div class="table table-responsive">
                        <table id="example" class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                          <thead>
                            <tr>
                              <th>{{ __('Event Title') }}</th>
                              <th>{{ __('Booking ID') }}</th>
                              <th>{{ __('Event Date') }}</th>
                              <th>{{ __('Booking Date') }}</th>
                              <th>{{ __('Action') }}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($bookings as $item)
                              @php
                                $event = $item
                                    ->event()
                                    ->where('language_id', $currentLanguageInfo->id)
                                    ->select('title', 'slug', 'event_id')
                                    ->first();
                                if (empty($event)) {
                                    $language = App\Models\Language::where('is_default', 1)->first();
                                    $event = $item
                                        ->event()
                                        ->where('language_id', $language->id)
                                        ->select('title', 'slug', 'event_id')
                                        ->first();
                                }
                              @endphp

                              @if (!empty($event))
                                <tr>
                                  <td>
                                    <a target="_blank"
                                      href="{{ route('event.details', ['slug' => $event->slug, 'id' => $event->event_id]) }}">{{ strlen($event->title) > 30 ? mb_substr($event->title, 0, 30) . '...' : $event->title }}</a>
                                  </td>
                                  <td>
                                    #{{ $item->booking_id }}
                                  </td>
                                  <td>{{ \Carbon\Carbon::parse($item->event_date)->translatedFormat('D, M d, Y h:i a') }}
                                  </td>
                                  <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('D, M d, Y h:i a') }}
                                  </td>
                                  <td><a href="{{ route('customer.booking_details', $item->id) }}"
                                      class="btn">{{ __('Details') }}</a></td>
                                </tr>
                              @endif
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                {{ $bookings->links() }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--====== End Dashboard Section ======-->
@endsection
