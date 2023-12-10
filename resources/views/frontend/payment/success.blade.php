@extends('frontend.layout')

@section('pageHeading')
  {{ __('Payment Success') }}
@endsection

@section('style')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-content.css') }}">
@endsection

@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="container">
      <div class="banner-inner">
        <h2 class="page-title">{{ __('Payment Success') }}</h2>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Payment Success') }}</li>
          </ol>
        </nav>
      </div>
    </div>
  </section>
  <!-- Page Banner End -->
@endsection

@section('content')
  <!-- Contact Section Start -->
  <div class="booking-message">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">

          <div class="booking-success">
            <div class="text-center">
              {{-- Add to calendar --}}
              @php
                if ($event->date_type == 'multiple') {
                    $start_date = str_replace('-', '', $event_date->start_date);
                    $start_time = str_replace(':', '', $event_date->start_time);
                    $end_date = str_replace('-', '', $event_date->end_date);
                    $end_time = str_replace(':', '', $event_date->end_time);

                    $s_time = $start_time - 5;
                    $e_time = $end_time - 5;
                } else {
                    $start_date = str_replace('-', '', $event->start_date);
                    $start_time = str_replace(':', '', $event->start_time);
                    $end_date = str_replace('-', '', $event->end_date);
                    $end_time = str_replace(':', '', $event->end_time);
                
                    $s_time = $start_time - 5;
                    $e_time = $end_time - 5;
                }
                
              @endphp
              <div class="dropdown show pt-4 pb-4">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-calendar-alt"></i> {{ __('Add this Event to Calendar') }}
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                  <a target="_blank" class="dropdown-item"
                    href="//calendar.google.com/calendar/u/0/r/eventedit?text={{ @$event->information->title }}&dates={{ $start_date }}T{{ $s_time }}/{{ $end_date }}T{{ $e_time }}&ctz={{ $websiteInfo->timezone }}&details=For+details,+click+here:+{{ route('event.details', [@$event->information->slug, $event->id]) }}&location={{ $event->event_type == 'online' ? 'Online' : @$event->information->address }}&sf=true">{{ __('Google Calendar') }}</a>
                  <a target="_blank" class="dropdown-item"
                    href="//calendar.yahoo.com/?v=60&view=d&type=20&TITLE={{ @$event->information->title }}&ST={{ $start_date }}T{{ $start_time }}&ET={{ $end_date }}T{{ $end_time }}&DUR=9959&DESC=For%20details%2C%20click%20here%3A%20{{ route('event.details', [@$event->information->slug, $event->id]) }}&in_loc={{ $event->event_type == 'online' ? 'Online' : @$event->information->address }}">{{ __('Yahoo') }}</a>
                </div>
              </div>
            </div>
            <div class="icon text-success"><i class="far fa-check-circle"></i></div>
            <h2 class="mb-3">{{ __('Success') }}!</h2>
            @if (request()->input('via') == 'offline')
              <p class="mb-1">{{ __('Your Booking Request was Successfully Placed') }}.</p>
              <p>{{ __('Please wait for confirmation') }}.</p>
            @else
              <p class="mb-1">{{ __('Your transaction was successful') }}.</p>
              <p>{{ __('We have sent you a mail with an invoice') }}.</p>
            @endif

            <p class="mb-0">{{ __('Thank you') }}.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Contact Section End -->
@endsection
