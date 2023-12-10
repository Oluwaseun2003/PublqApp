@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('User Details') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('admin.dashboard')}}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('User Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Registered Users') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('User Details') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-3">
          <div class="card">
            <div class="card-header">
              <div class="h4 card-title">{{ __('Profile Picture') }}</div>
            </div>

            <div class="card-body text-center py-4">
              <img src="{{ empty($userInfo->image) ? asset('assets/admin/img/profile.jpg') : asset('assets/admin/img/users/' . $userInfo->image) }}" alt="user image" width="150">
            </div>
          </div>
        </div>

        <div class="col-md-9">
          <div class="card">
            <div class="card-header">
              <div class="h4 card-title">{{ __('User Information') }}</div>
            </div>

            <div class="card-body">
              <div class="payment-information">
                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('Name') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-10">
                    {{ $userInfo->first_name . ' ' . $userInfo->last_name }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('Username') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-10">
                    {{ $userInfo->username }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('Email') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-10">
                    {{ $userInfo->email }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('Phone') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-10">
                    {{ $userInfo->contact_number }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('Address') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-10">
                    {{ $userInfo->address }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('City') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-10">
                    {{ $userInfo->city }}
                  </div>
                </div>

                <div class="row mb-2">
                  <div class="col-lg-2">
                    <strong>{{ __('State') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-10">
                    {{ $userInfo->state }}
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-2">
                    <strong>{{ __('Country') . ' :' }}</strong>
                  </div>
                  <div class="col-lg-10">
                    {{ $userInfo->country }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
