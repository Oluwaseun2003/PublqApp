@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->customer_wishlist_page_title ?? __('My Wishlist') }}
  @else
    {{ __('My Wishlist') }}
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
            {{ $pageHeading->customer_wishlist_page_title ?? __('My Wishlist') }}
          @else
            {{ __('My Wishlist') }}
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
                {{ $pageHeading->customer_wishlist_page_title ?? __('My Wishlist') }}
              @else
                {{ __('My Wishlist') }}
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
                    <h4>{{ __('Wishlist') }}</h4>
                  </div>
                  <div class="main-info">
                    @if (count($wishlist) > 0)
                      <div class="main-table">
                        <div class="table-responsiv">
                          <table class="dataTables_wrapper dt-responsive table-striped dt-bootstrap4 w-100">
                            <thead>
                              <tr>
                                <th>{{ __('Event Name') }}</th>
                                <th>{{ __('Action') }}</th>
                              </tr>
                            </thead>
                            <tbody>
                              @foreach ($wishlist as $item)
                                @php
                                  $content = DB::table('event_contents')
                                      ->where('event_id', $item->event_id)
                                      ->select('title', 'slug')
                                      ->first();
                                @endphp
                                @if ($content)
                                  <tr>
                                    <td>
                                      <a target="_blank"
                                        href="{{ route('event.details', [$content->slug, $item->event_id]) }}">{{ $content->title }}</a>
                                    </td>
                                    <td>
                                      <a href="{{ route('event.details', [$content->slug, $item->event_id]) }}"
                                        class="btn mb-1">{{ __('Details') }}</a>
                                      <a href="{{ route('remove.wishlist', $item->event_id) }}"
                                        class="btn btn-danger bg-danger text-white  mb-1">{{ __('Remove') }}</a>
                                    </td>
                                  </tr>
                                @endif
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                      </div>
                    @else
                      <p class="text-center">{{ __('No Event Found') . '.' }}</p>
                    @endif
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

