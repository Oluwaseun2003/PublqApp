@extends('frontend.layout')
@section('pageHeading')
    @if (!empty($pageHeading))
        {{ $pageHeading->event_page_title ?? __('Events') }}
    @else
        {{ __('Events') }}
    @endif
@endsection

@php
    $metaKeywords = !empty($seo->meta_keyword_event) ? $seo->meta_keyword_event : '';
    $metaDescription = !empty($seo->meta_description_event) ? $seo->meta_description_event : '';
@endphp
@section('meta-keywords', "{{ $metaKeywords }}")
@section('meta-description', "$metaDescription")

@section('hero-section')
    <!-- Page Banner Start -->
    <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
        data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
        <div class="container">
            <div class="banner-inner">
                <h2 class="page-title">
                    @if (!empty($pageHeading))
                        {{ $pageHeading->event_page_title ?? __('Events') }}
                    @else
                        {{ __('Events') }}
                    @endif
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if (!empty($pageHeading))
                                {{ $pageHeading->event_page_title ?? __('Events') }}
                            @else
                                {{ __('Events') }}
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
    <!-- Event Page Start -->
    <section class="event-page-section py-120 rpy-100">
        <div class="container container-custom">
            <div class="row">
                <div class="col-lg-3">
                    <div class="sidebar rmb-75">
                        <div class="widget widget-search">
                            <form action="{{ route('events') }}">

                                <input type="text" name="search-input"
                                    value="{{ !empty(request()->input('search-input')) ? request()->input('search-input') : '' }}"
                                    placeholder="{{ __('Search') }}.....">
                                @if (request()->filled('category'))
                                    <input type="hidden" id="category-id" name="category"
                                        value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">
                                @endif
                                @if (request()->filled('event'))
                                    <input type="hidden" name="event"
                                        value="{{ !empty(request()->input('event')) ? request()->input('event') : '' }}">
                                @endif
                                @if (request()->filled('min'))
                                    <input type="hidden" name="min"
                                        value="{{ !empty(request()->input('min')) ? request()->input('min') : '' }}">
                                @endif

                                @if (request()->filled('max'))
                                    <input type="hidden" name="max"
                                        value="{{ !empty(request()->input('max')) ? request()->input('max') : '' }}">
                                @endif

                                @if (request()->filled('location'))
                                    <input type="hidden" name="location"
                                        value="{{ !empty(request()->input('location')) ? request()->input('location') : '' }}">
                                @endif

                                @if (request()->filled('dated'))
                                    <input type="hidden" name="dates"
                                        value="{{ !empty(request()->input('dates')) ? request()->input('dates') : '' }}">
                                @endif
                                <button type="submit" class="fa fa-search event-search-button"></button>
                            </form>
                        </div>
                        {{-- date filter input --}}
                        <div class="widget widget-dropdown">
                            <div class="form-group">
                                <label for="">{{ __('Filter by Date') }}</label>
                                <input type="text" placeholder="{{ __('Start/End Date') }}"
                                    @if (request()->input('dates') && request()->input('dates')) value="{{ request()->input('dates') }}" @endif
                                    name="daterange" />
                            </div>
                        </div>
                        {{-- location input --}}
                        <div class="widget widget-search">
                            <form action="{{ route('events') }}">

                                @if (request()->filled('search-input'))
                                    <input type="hidden" name="search-input"
                                        value="{{ !empty(request()->input('search-input')) ? request()->input('search-input') : '' }}">
                                @endif

                                @if (request()->filled('category'))
                                    <input type="hidden" id="category-id" name="category"
                                        value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">
                                @endif

                                @if (request()->filled('event'))
                                    <input type="hidden" name="event"
                                        value="{{ !empty(request()->input('event')) ? request()->input('event') : '' }}">
                                @endif

                                <input type="text" name="location"
                                    value="{{ !empty(request()->input('location')) ? request()->input('location') : '' }}"
                                    placeholder="{{ __('Enter Location') }}">

                                @if (request()->filled('dates'))
                                    <input type="hidden" name="dates"
                                        value="{{ !empty(request()->input('dates')) ? request()->input('dates') : '' }}">
                                @endif

                                @if (request()->filled('min'))
                                    <input type="hidden" name="min"
                                        value="{{ !empty(request()->input('min')) ? request()->input('min') : '' }}">
                                @endif

                                @if (request()->filled('max'))
                                    <input type="hidden" name="max"
                                        value="{{ !empty(request()->input('max')) ? request()->input('max') : '' }}">
                                @endif
                                <button type="submit" class="fa fa-search  event-search-button"></button>
                            </form>
                        </div>
                        <div class="widget widget-cagegory">
                            <h5 class="widget-title">{{ __('Category') }}</h5>
                            <form action="{{ route('events') }}" id="catForm">
                                @if (request()->filled('search-input'))
                                    <input type="hidden" name="search-input"
                                        value="{{ !empty(request()->input('search-input')) ? request()->input('search-input') : '' }}">
                                @endif

                                <select id="category" name="category" class="widget-select">
                                    <option disabled>{{ __('Select a Category') }}</option>
                                    <option value="">{{ __('All') }}</option>
                                    @foreach ($information['categories'] as $item)
                                        <option {{ request()->input('category') == $item->slug ? 'selected' : '' }}
                                            value="{{ $item->slug }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                {{-- form hidden input --}}

                                @if (request()->filled('location'))
                                    <input type="hidden" name="location"
                                        value="{{ !empty(request()->input('location')) ? request()->input('location') : '' }}">
                                @endif

                                @if (request()->filled('event'))
                                    <input type="hidden" name="event"
                                        value="{{ !empty(request()->input('event')) ? request()->input('event') : '' }}">
                                @endif

                                @if (request()->filled('min'))
                                    <input type="hidden" name="min"
                                        value="{{ !empty(request()->input('min')) ? request()->input('min') : '' }}">
                                @endif

                                @if (request()->filled('max'))
                                    <input type="hidden" name="max"
                                        value="{{ !empty(request()->input('max')) ? request()->input('max') : '' }}">
                                @endif

                                @if (request()->filled('dates'))
                                    <input type="hidden" name="dates"
                                        value="{{ !empty(request()->input('dates')) ? request()->input('dates') : '' }}">
                                @endif
                            </form>
                        </div>
                        <div class="widget widget-location">
                            <h5 class="widget-title">{{ __('Events') }}</h5>
                            <div class="widget-radio">
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input"
                                        {{ request()->input('event') == 'online' ? 'checked' : '' }} value="online"
                                        name="event" id="radio1">
                                    <label class="custom-control-label" for="radio1">{{ __('Online Events') }}</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" value="venue"
                                        {{ request()->input('event') == 'venue' ? 'checked' : '' }} name="event"
                                        id="radio2">
                                    <label class="custom-control-label" for="radio2">{{ __('Venue Events') }}</label>
                                </div>
                            </div>
                        </div>


                        <div class="widget price-filter-widget">
                            <h5 class="widget-title">{{ __('Price Filter') }}</h5>
                            <div class="price-slider-range" id="range-slider"></div>
                            <div class="price-btn">
                                <input type="text" dir="ltr" id="price"
                                    value="{{ request()->input('min') }}" readonly>
                                <button class="theme-btn" id="slider_submit">{{ __('Price Filter') }}</button>
                            </div>
                        </div>
                        @if (!empty(showAd(2)))
                            <div class="text-center mt-4">
                                {!! showAd(2) !!}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="event-page-content">
                        <div class="row">
                            @if (count($information['events']) > 0)
                                @foreach ($information['events'] as $event)
                                    <div class="col-sm-6 col-xl-4">
                                        <div class="event-item">
                                            <div class="event-image">
                                                <a href="{{ route('event.details', [$event->slug, $event->id]) }}">
                                                    <img class="lazy"
                                                        data-src="{{ asset('assets/admin/img/event/thumbnail/' . $event->thumbnail) }}"
                                                        alt="Event">
                                                </a>
                                            </div>
                                            <div class="event-content">
                                                <ul class="time-info" dir="ltr">
                                                    @php
                                                        if ($event->date_type == 'multiple') {
                                                            $event_date = eventLatestDates($event->id);
                                                            $date = strtotime(@$event_date->start_date);
                                                        } else {
                                                            $date = strtotime($event->start_date);
                                                        }
                                                    @endphp
                                                    <li>
                                                        <i class="far fa-calendar-alt"></i>
                                                        <span>
                                                            {{ \Carbon\Carbon::parse($date)->translatedFormat('d M') }}
                                                        </span>
                                                    </li>

                                                    <li>
                                                        <i class="far fa-hourglass"></i>
                                                        <span title="Event Duration">
                                                            {{ $event->date_type == 'multiple' ? @$event_date->duration : $event->duration }}
                                                        </span>
                                                    </li>
                                                    <li>
                                                        <i class="far fa-clock"></i>
                                                        <span>
                                                            @php
                                                                $start_time = strtotime($event->start_time);
                                                            @endphp
                                                            {{ \Carbon\Carbon::parse($start_time)->translatedFormat('h:s A') }}
                                                        </span>
                                                    </li>
                                                </ul>
                                                @if ($event->organizer_id != null)
                                                    @php
                                                        $organizer = App\Models\Organizer::where('id', $event->organizer_id)->first();
                                                    @endphp
                                                    @if ($organizer)
                                                        <a href="{{ route('frontend.organizer.details', [$organizer->id, str_replace(' ', '-', $organizer->username)]) }}"
                                                            class="organizer">{{ __('By') }}&nbsp;&nbsp;{{ $organizer->username }}</a>
                                                    @endif
                                                @else
                                                    @php
                                                        $admin = App\Models\Admin::first();
                                                    @endphp
                                                    <a href="{{ route('frontend.organizer.details', [$admin->id, str_replace(' ', '-', $admin->username), 'admin' => 'true']) }}"
                                                        class="organizer">{{ __('By') }} {{ $admin->username }}</a>
                                                @endif
                                                <h5>
                                                    <a href="{{ route('event.details', [$event->slug, $event->id]) }}">
                                                        @if (strlen($event->title) > 70)
                                                            {{ mb_substr($event->title, 0, 70) . '...' }}
                                                        @else
                                                            {{ $event->title }}
                                                        @endif
                                                    </a>
                                                </h5>
                                                @php
                                                    $desc = strip_tags($event->description);
                                                @endphp

                                                @if (strlen($desc) > 100)
                                                    <p class="event-description">{{ mb_substr($desc, 0, 100) . '....' }}
                                                    </p>
                                                @else
                                                    <p class="event-description">{{ $desc }}</p>
                                                @endif
                                                @php
                                                    if ($event->event_type == 'online') {
                                                        $ticket = App\Models\Event\Ticket::where('event_id', $event->id)
                                                            ->orderBy('price', 'asc')
                                                            ->first();
                                                    } else {
                                                        $ticket = App\Models\Event\Ticket::where([['event_id', $event->id], ['price', '!=', null]])
                                                            ->orderBy('price', 'asc')
                                                            ->first();
                                                        if (empty($ticket)) {
                                                            $ticket = App\Models\Event\Ticket::where([['event_id', $event->id], ['f_price', '!=', null]])
                                                                ->orderBy('price', 'asc')
                                                                ->first();
                                                        }
                                                    }
                                                    $event_count = DB::table('tickets')
                                                        ->where('event_id', $event->id)
                                                        ->get()
                                                        ->count();
                                                @endphp

                                                <div class="price-remain">
                                                    <div class="location">
                                                        @if ($event->event_type == 'venue')
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            <span>
                                                                @if ($event->city != null)
                                                                    {{ $event->city }}
                                                                @endif
                                                                @if ($event->country)
                                                                    , {{ $event->country }}
                                                                @endif
                                                            </span>
                                                        @else
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            <span>{{ __('Online') }}</span>
                                                        @endif
                                                    </div>
                                                    <span>
                                                        @if ($ticket)
                                                            @if ($ticket->event_type == 'online')
                                                                @if ($ticket->pricing_type != 'free')
                                                                    <span class="price" dir="ltr">
                                                                        @if ($ticket->early_bird_discount == 'enable')
                                                                            @php
                                                                                $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                                                                            @endphp

                                                                            @if ($ticket->early_bird_discount_type == 'fixed' && !$discount_date->isPast())
                                                                                @php
                                                                                    $calculate_price = $ticket->price - $ticket->early_bird_discount_amount;
                                                                                @endphp
                                                                                {{ symbolPrice($calculate_price) }}
                                                                                <span>
                                                                                    <del>
                                                                                        {{ symbolPrice($ticket->price) }}
                                                                                    </del>
                                                                                </span>
                                                                            @elseif ($ticket->early_bird_discount_type == 'percentage' && !$discount_date->isPast())
                                                                                @php
                                                                                    $p_price = ($ticket->price * $ticket->early_bird_discount_amount) / 100;
                                                                                    $calculate_price = $ticket->price - $p_price;
                                                                                @endphp

                                                                                {{ symbolPrice($calculate_price) }}
                                                                                <span>
                                                                                    <del>
                                                                                        {{ symbolPrice($ticket->price) }}
                                                                                    </del>
                                                                                </span>
                                                                            @else
                                                                                @php
                                                                                    $calculate_price = $ticket->price;
                                                                                @endphp
                                                                                {{ symbolPrice($calculate_price) }}
                                                                            @endif
                                                                        @else
                                                                            @php
                                                                                $calculate_price = $ticket->price;
                                                                            @endphp
                                                                            {{ symbolPrice($calculate_price) }}
                                                                        @endif

                                                                    </span>
                                                                @else
                                                                    <span class="price">{{ __('Free') }}</span>
                                                                @endif
                                                            @endif
                                                            @if ($ticket->event_type == 'venue')
                                                                @if ($ticket->pricing_type == 'variation')
                                                                    <span class="price" dir="ltr">
                                                                        @php
                                                                            $variation = json_decode($ticket->variations, true);
                                                                            $v_min_price = array_reduce(
                                                                                $variation,
                                                                                function ($a, $b) {
                                                                                    return $a['price'] < $b['price'] ? $a : $b;
                                                                                },
                                                                                array_shift($variation),
                                                                            );
                                                                            $price = $v_min_price['price'];
                                                                        @endphp
                                                                        <span class="price">
                                                                            @if ($currentLanguageInfo->direction == 1)
                                                                                <strong>{{ $event_count > 1 ? '*' : '' }}</strong>
                                                                            @endif
                                                                            @if ($ticket->early_bird_discount == 'enable')
                                                                                @php
                                                                                    $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                                                                                @endphp
                                                                                @if ($ticket->early_bird_discount_type == 'fixed' && !$discount_date->isPast())
                                                                                    @php
                                                                                        $calculate_price = $price - $ticket->early_bird_discount_amount;
                                                                                    @endphp
                                                                                    {{ symbolPrice($calculate_price) }}
                                                                                    <span><del>
                                                                                            {{ symbolPrice($price) }}
                                                                                        </del></span>
                                                                                @elseif ($ticket->early_bird_discount_type == 'percentage' && !$discount_date->isPast())
                                                                                    @php
                                                                                        $p_price = ($price * $ticket->early_bird_discount_amount) / 100;
                                                                                        $calculate_price = $p_price - $price;
                                                                                    @endphp
                                                                                    {{ symbolPrice($calculate_price) }}

                                                                                    <span>
                                                                                        <del>
                                                                                            {{ symbolPrice($price) }}
                                                                                        </del>
                                                                                    </span>
                                                                                @else
                                                                                    @php
                                                                                        $calculate_price = $price;
                                                                                    @endphp
                                                                                    {{ symbolPrice($calculate_price) }}
                                                                                @endif
                                                                            @else
                                                                                @php
                                                                                    $calculate_price = $price;
                                                                                @endphp
                                                                                {{ symbolPrice($calculate_price) }}
                                                                            @endif
                                                                            @if ($currentLanguageInfo->direction != 1)
                                                                                <strong>{{ $event_count > 1 ? '*' : '' }}</strong>
                                                                            @endif
                                                                        </span>
                                                                    </span>
                                                                @elseif($ticket->pricing_type == 'normal')
                                                                    <span class="price" dir="ltr">
                                                                        @if ($currentLanguageInfo->direction == 1)
                                                                            <strong>{{ $event_count > 1 ? '*' : '' }}</strong>
                                                                        @endif
                                                                        @if ($ticket->early_bird_discount == 'enable')
                                                                            {{-- check discount date over or not --}}
                                                                            @php
                                                                                $discount_date = Carbon\Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
                                                                            @endphp

                                                                            @if ($ticket->early_bird_discount_type == 'fixed' && !$discount_date->isPast())
                                                                                @php
                                                                                    $calculate_price = $ticket->price - $ticket->early_bird_discount_amount;
                                                                                @endphp

                                                                                {{ symbolPrice($calculate_price) }}
                                                                                <span>
                                                                                    <del>
                                                                                        {{ symbolPrice($ticket->price) }}
                                                                                    </del>
                                                                                </span>
                                                                            @elseif ($ticket->early_bird_discount_type == 'percentage' && !$discount_date->isPast())
                                                                                @php
                                                                                    $p_price = ($ticket->price * $ticket->early_bird_discount_amount) / 100;
                                                                                    $calculate_price = $ticket->price - $p_price;
                                                                                @endphp
                                                                                {{ symbolPrice($calculate_price) }}

                                                                                <span>
                                                                                    <del>
                                                                                        {{ $ticket->price }}
                                                                                        {{ symbolPrice($ticket->price) }}
                                                                                    </del>
                                                                                </span>
                                                                            @else
                                                                                @php
                                                                                    $calculate_price = $ticket->price;
                                                                                @endphp
                                                                                {{ symbolPrice($calculate_price) }}
                                                                            @endif
                                                                        @else
                                                                            @php
                                                                                $calculate_price = $ticket->price;
                                                                            @endphp
                                                                            {{ symbolPrice($calculate_price) }}
                                                                        @endif
                                                                        @if ($currentLanguageInfo->direction != 1)
                                                                            <strong>{{ $event_count > 1 ? '*' : '' }}</strong>
                                                                        @endif
                                                                    </span>
                                                                @else
                                                                    <span class="price">
                                                                        {{ __('Free') }}
                                                                        <strong>{{ $event_count > 1 ? '*' : '' }}</strong>
                                                                    </span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            @if (Auth::guard('customer')->check())
                                                @php
                                                    $customer_id = Auth::guard('customer')->user()->id;
                                                    $event_id = $event->id;
                                                    $checkWishList = checkWishList($event_id, $customer_id);
                                                @endphp
                                            @else
                                                @php
                                                    $checkWishList = false;
                                                @endphp
                                            @endif
                                            <a href="{{ $checkWishList == false ? route('addto.wishlist', $event->id) : route('remove.wishlist', $event->id) }}"
                                                class="wishlist-btn {{ $checkWishList == true ? 'bg-success' : '' }}">
                                                <i class="far fa-bookmark"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-lg-12">
                                    <h3 class="text-center">{{ __('No Event Found') }}</h3>
                                </div>
                            @endif
                        </div>
                        <ul class="pagination flex-wrap pt-10">
                            {{ $information['events']->links() }}
                        </ul>
                        @if (!empty(showAd(3)))
                            <div class="text-center mt-4">
                                {!! showAd(3) !!}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Event Page End -->

    <form id="filtersForm" class="d-none" action="{{ route('events') }}" method="GET">
        <input type="hidden" id="category-id" name="category"
            value="{{ !empty(request()->input('category')) ? request()->input('category') : '' }}">

        <input type="hidden" id="event" name="event"
            value="{{ !empty(request()->input('event')) ? request()->input('event') : '' }}">

        <input type="hidden" id="min-id" name="min"
            value="{{ !empty(request()->input('min')) ? request()->input('min') : '' }}">

        <input type="hidden" id="max-id" name="max"
            value="{{ !empty(request()->input('max')) ? request()->input('max') : '' }}">

        <input type="hidden" name="search-input"
            value="{{ !empty(request()->input('search-input')) ? request()->input('search-input') : '' }}">
        <input type="hidden" name="location"
            value="{{ !empty(request()->input('location')) ? request()->input('location') : '' }}">

        <input type="hidden" id="dates-id" name="dates"
            value="{{ !empty(request()->input('dates')) ? request()->input('dates') : '' }}">

        <button type="submit" id="submitBtn"></button>
    </form>
@endsection

@section('custom-script')
    <script type="text/javascript" src="{{ asset('assets/front/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/front/js/daterangepicker.min.js') }}"></script>

    <script>
        let min_price = {!! htmlspecialchars($information['min']) !!};
        let max_price = {!! htmlspecialchars($information['max']) !!};
        let symbol = "{!! htmlspecialchars($basicInfo->base_currency_symbol) !!}";
        let position = "{!! htmlspecialchars($basicInfo->base_currency_symbol_position) !!}";
        let curr_min = {!! !empty(request()->input('min')) ? htmlspecialchars(request()->input('min')) : 5 !!};
        let curr_max = {!! !empty(request()->input('max')) ? htmlspecialchars(request()->input('max')) : 800 !!};
    </script>

    <script src="{{ asset('assets/front/js/custom_script.js') }}"></script>
@endsection
