@extends('frontend.layout')
@section('pageHeading')
  @if (!empty($pageHeading))
    {{ $pageHeading->customer_edit_profile_page_title ?? __('Edit Profile') }}
  @else
    {{ __('Edit Profile') }}
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
            {{ $pageHeading->customer_edit_profile_page_title ?? __('Edit Profile') }}
          @else
            {{ __('Edit Profile') }}
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
                {{ $pageHeading->customer_edit_profile_page_title ?? __('Edit Profile') }}
              @else
                {{ __('Edit Profile') }}
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
                        {{ $pageHeading->customer_edit_profile_page_title ?? __('Edit Profile') }}
                      @else
                        {{ __('Edit Profile') }}
                      @endif
                    </h4>
                  </div>
                  <div class="edit-info-area">
                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                      @csrf

                      <div class="file-upload-area">
                        <div class="file-edit">
                          <input type="file" name="photo" id="imageUpload">
                          <label for="imageUpload"></label>
                        </div>
                        <div class="file-preview">
                          @if (Auth::guard('customer')->user()->photo == null)
                            <div id="imagePreview" class="bg-img"
                              data-bg-image="{{ asset('assets/front/images/profile.jpg') }}">
                            </div>
                          @else
                            <div id="imagePreview" class="bg-img"
                              data-bg-image="{{ asset('assets/admin/img/customer-profile/' . Auth::guard('customer')->user()->photo) }}">
                            </div>
                          @endif
                        </div>
                      </div>
                      @error('photo')
                        <p class="text-danger">{{ $message }}</p>
                      @enderror

                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="">{{ __('First Name') }} <span>*</span> </label>
                            <input type="text" class="form_control"
                              value="{{ Auth::guard('customer')->user()->fname }}" placeholder="{{ __('First Name') }}"
                              name="fname" required>
                            @error('fname')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="">{{ __('Last Name') }} <span>*</span></label>
                            <input type="text" class="form_control" placeholder="{{ __('Last Name') }}" name="lname"
                              value="{{ Auth::guard('customer')->user()->lname }}" required>
                            @error('lname')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="">{{ __('Email Address') }} <span>*</span></label>
                            <input type="email" class="form_control" placeholder="{{ __('Email') }}" name="email"
                              value="{{ Auth::guard('customer')->user()->email }}">
                            @error('email')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="">{{ __('Username') }} <span>*</span></label>
                            <input type="text" class="form_control" placeholder="{{ __('Username') }}" name="username"
                              value="{{ Auth::guard('customer')->user()->username }}">
                            @error('username')
                              <p class="text-danger">{{ $message }}</p>
                            @enderror
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="">{{ __('Phone') }}</label>
                            <input type="text" class="form_control" placeholder="{{ __('Phone') }}" name="phone"
                              value="{{ Auth::guard('customer')->user()->phone }}">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="">{{ __('Country') }}</label>
                            <input type="text" class="form_control" placeholder="{{ __('Country') }}" name="country"
                              value="{{ Auth::guard('customer')->user()->country }}">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="">{{ __('City') }}</label>
                            <input type="text" class="form_control" placeholder="{{ __('City') }}" name="city"
                              value="{{ Auth::guard('customer')->user()->city }}">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="">{{ __('State') }}</label>
                            <input type="text" class="form_control" placeholder="{{ __('State') }}"
                              name="state" value="{{ Auth::guard('customer')->user()->state }}">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label for="">{{ __('Zip-code') }}</label>
                            <input type="text" class="form_control" placeholder="{{ __('Zip-code') }}"
                              name="zip_code" value="{{ Auth::guard('customer')->user()->zip_code }}">
                          </div>
                        </div>

                        <div class="col-lg-12">
                          <div class="form-group">
                            <label for="">{{ __('Address') }}</label>
                            <textarea name="address" class="form_control" placeholder="{{ __('Address') }}">{{ Auth::guard('customer')->user()->address }}</textarea>
                          </div>
                        </div>
                        <div class="col-lg-12">
                          <div class="form-button">
                            <button class="btn form-btn">{{ __('Update') }}</button>
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
