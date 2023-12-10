@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->support_ticket_create_page_title ?? __('Create a support ticket') }}
  @else
    {{ __('Create a support ticket') }}
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
            {{ $pageHeading->support_ticket_create_page_title ?? __('Create a support ticket') }}
          @else
            {{ __('Create a support ticket') }}
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
                {{ $pageHeading->support_ticket_create_page_title ?? __('Create a support ticket') }}
              @else
                {{ __('Create a support ticket') }}
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
                  @if (Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                  @endif
                  <div class="title">
                    <h4>{{ __('Submit a Ticket') }}</h4>
                  </div>
                  <div class="edit-info-area">
                    <form action="{{ route('customer.support_ticket.store') }}" method="POST"
                      enctype="multipart/form-data">
                      @csrf

                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="">{{ __('Email') }}</label>
                            <input type="email" class="form_control"
                              value="{{ Auth::guard('customer')->user()->email }}"
                              placeholder="{{ __('Email Address') }}" name="email" required>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="">{{ __('Subject') }}</label>
                            <input type="text" class="form_control" placeholder="{{ __('Subject') }}" name="subject"
                              required>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="">{{ __('Description') }}</label>
                            <textarea name="description" class="form_control" placeholder="{{ __('Description') }}"></textarea>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="attachment">{{ __('Attachment') }}</label>
                            <input type="file" class="form-control" id="attachment" name="attachment">
                            @error('attachment')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>

                        <div class="col-lg-12">
                          <div class="form-button">
                            <button class="btn form-btn">{{ __('Submit') }}</button>
                          </div>
                        </div>
                      </div>
                    </form>
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
