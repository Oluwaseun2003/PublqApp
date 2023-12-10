@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->customer_change_password_page_title ?? __('Change Password') }}
  @else
    {{ __('Change Password') }}
  @endif
@endsection
@section('hero-section')
  <!-- Page Banner Start -->
  <section class="page-banner overlay pt-120 pb-125 rpt-90 rpb-95 lazy"
    data-bg="{{ asset('assets/admin/img/' . $basicInfo->breadcrumb) }}">
    <div class="banner-inner">
      <h2 class="page-title">
        @if (!empty($pageHeading))
          {{ $pageHeading->customer_change_password_page_title ?? __('Change Password') }}
        @else
          {{ __('Change Password') }}
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
              {{ $pageHeading->customer_change_password_page_title ?? __('Change Password') }}
            @else
              {{ __('Change Password') }}
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
                    <h4>
                      @if (!empty($pageHeading))
                        {{ $pageHeading->customer_change_password_page_title ?? __('Change Password') }}
                      @else
                        {{ __('Change Password') }}
                      @endif
                    </h4>
                    
                  </div>
                  <div class="edit-info-area">
                    <form action="{{ route('customer.password.update') }}" method="POST" enctype="multipart/form-data">
                      @csrf

                      <div class="row">
                        <div class="col-lg-12">
                          <div class="form-group">
                            <input type="password" class="form_control" placeholder="{{ __('Enter Current Password') }}"
                              name="current_password" required>
                            @error('current_password')
                              <p class="text-danger"> <strong>{{ $message }}</strong></p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <input type="password" class="form_control" placeholder="{{ __('New Password') }}"
                              name="new_password" required>
                            @error('new_password')
                              <p class="text-danger"> <strong>{{ $message }}</strong></p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <input type="password" class="form_control" placeholder="{{ __('Confirm Password') }}"
                              name="new_password_confirmation" required>
                            @error('new_password_confirmation')
                              <p class="text-danger"> <strong>{{ $message }}</strong></p>
                            @enderror
                          </div>
                        </div>


                        <div class="col-lg-12">
                          <div class="form-button">
                            <button class="btn form-btn pt-2 pb-2 pr-4 pl-4">{{ __('Update') }}</button>
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
