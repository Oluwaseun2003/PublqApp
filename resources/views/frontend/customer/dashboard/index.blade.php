@extends('frontend.layout')
@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->customer_dashboard_page_title ?? __('Dashboard') }}
    @else
        {{ __('Dashboard') }}
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
                        {{ $pageHeading->customer_dashboard_page_title ?? __('Dashboard') }}
                    @else
                        {{ __('Dashboard') }}
                    @endif
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (!empty($pageHeading))
                                {{ $pageHeading->customer_dashboard_page_title ?? __('Dashboard') }}
                            @else
                                {{ __('Dashboard') }}
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
                    <div class="row mb-5">
                        <div class="col-lg-12">
                            <div class="user-profile-details">
                                <div class="account-info">
                                    <div class="title">
                                        <h4>{{ __('Account Information') }}</h4>
                                    </div>
                                    <div class="main-info">
                                        <h5>{{ __('User') }}</h5>
                                        <ul class="list">
                                            @if (Auth::guard('customer')->user()->email != null)
                                                <li><b>{{ __('Email') . ' : ' }}</b></li>
                                            @endif
                                            @if (Auth::guard('customer')->user()->username != null)
                                                <li><b>{{ __('Username') . ' : ' }}</b></li>
                                            @endif
                                            @if (Auth::guard('customer')->user()->phone != null)
                                                <li><b>{{ __('Phone') . ' : ' }}</b></li>
                                            @endif
                                            @if (Auth::guard('customer')->user()->address != null)
                                                <li><b>{{ __('Address') . ' : ' }}</b></li>
                                            @endif
                                            @if (Auth::guard('customer')->user()->country != null)
                                                <li><b>{{ __('Country') . ' : ' }}</b></li>
                                            @endif
                                            @if (Auth::guard('customer')->user()->city != null)
                                                <li><b>{{ __('City') . ' : ' }}</b></li>
                                            @endif
                                            @if (Auth::guard('customer')->user()->state != null)
                                                <li><b>{{ __('State') . ' : ' }}</b></li>
                                            @endif
                                            @if (Auth::guard('customer')->user()->zip_code != null)
                                                <li><b>{{ __('Zip-code') . ' : ' }} </b></li>
                                            @endif
                                        </ul>
                                        <ul class="list w-60p">
                                            <li>{{ Auth::guard('customer')->user()->email }}</li>
                                            <li>{{ Auth::guard('customer')->user()->username }}</li>
                                            <li>{{ Auth::guard('customer')->user()->phone }}</li>
                                            <li>{{ Auth::guard('customer')->user()->address }}</li>
                                            <li>{{ Auth::guard('customer')->user()->country }}</li>
                                            <li>{{ Auth::guard('customer')->user()->city }}</li>
                                            <li>{{ Auth::guard('customer')->user()->state }}</li>
                                            <li>{{ Auth::guard('customer')->user()->zip_code }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="account-info">
                                <div class="title">
                                    <h4>{{ __('Recent Bookings') }}</h4>
                                </div>
                                <div class="main-info">
                                    <div class="main-table">
                                        <div class="table-responsiv">
                                            <table id="example"
                                                class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Event Title') }}</th>
                                                        <th>{{ __('Organizer') }}</th>
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
                                                                    @if ($item->organizer)
                                                                        <a target="_blank"
                                                                            href="{{ route('frontend.organizer.details', [$item->organizer->id, str_replace(' ', '-', $item->organizer->username)]) }}">{{ $item->organizer->username }}</a>
                                                                    @else
                                                                        <span
                                                                            class="badge badge-success">{{ __('Admin') }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    {{ \Carbon\Carbon::parse($item->event_date)->translatedFormat('D, M d, Y h:i a') }}
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== End Dashboard Section ======-->
@endsection
