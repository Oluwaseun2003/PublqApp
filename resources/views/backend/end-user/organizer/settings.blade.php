@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Settings') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Organizers Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Settings') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12 mx-auto">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Settings') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-6 mx-auto">
              <form id="organizerSettingForm" action="{{ route('admin.organizer_management.setting.update') }}"
                method="post">
                @csrf
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <div class="custom-control custom-checkbox organizer_admin_approvalbtn">
                        <input type="checkbox" {{ $setting->organizer_admin_approval == 1 ? 'checked' : '' }}
                          name="organizer_admin_approval" class="custom-control-input" id="organizer_admin_approval">
                        <label class="custom-control-label"
                          for="organizer_admin_approval">{{ __('Needs Admin Approval') }}</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" {{ $setting->organizer_email_verification == 1 ? 'checked' : '' }}
                          name="organizer_email_verification" class="custom-control-input" id="customCheck2">
                        <label class="custom-control-label" for="customCheck2">{{ __('Email Verificaiton') }}</label>
                      </div>
                    </div>
                  </div>
                  <div
                    class="col-lg-12 {{ $setting->organizer_admin_approval == 0 ? 'd-none' : '' }} admin_approval_notice">
                    <div class="form-group">
                      <label for="">{{ __('Admin Approval Notice') }}</label>
                      <textarea id="" rows="4" name="admin_approval_notice" class="form-control">{{ $setting->admin_approval_notice }}</textarea>
                      <p class="text-warning">{{ __('This text will be visible in organizer Dashboard') }}</p>
                    </div>
                  </div>


                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="organizerSettingBtn" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

