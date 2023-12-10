@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Tax & Commission') }}</h4>
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
        <a href="#">{{ __('Event Bookings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Settings') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Tax & Commission') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.event_booking.settings.update_tax_commission') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Update Tax & Commission') }}</div>
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <div class="form-group">
                  <label>{{ __('Tax') . '* (%)' }}</label>
                  <input type="number" step="0.01" class="form-control" name="tax"
                    value="{{ $content->tax != null ? $content->tax : '' }}" placeholder="{{ __('Enter Tax Amount') }}">
                  @error('tax')
                    <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="col-lg-6 offset-lg-3">
                <div class="form-group">
                  <label for="">{{ __('Commission') }} (%)</label>
                  <input type="text" class="form-control" name="commission"
                    value="{{ $content ? $content->commission : '' }}" placeholder="{{ __('Enter Commission') }}">
                  @if ($errors->has('commission'))
                    <p class="mb-0 text-danger">{{ $errors->first('commission') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
