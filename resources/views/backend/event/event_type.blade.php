@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Choose Event Type') }}</h4>
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
        <a href="#">{{ __('Events Management') }}</a>
      </li>

      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Choose Event Type') }}</a>
      </li>
    </ul>
  </div>

  <div class="product-type">

    <div class="row">
      <div class="col-lg-6">
        <a href="{{ route('add.event.event', ['type' => 'online']) }}" class="d-block text-decoration-none">
          <div class="card card-stats card-round p-50">
            <div class="card-body ">
              <div class="row align-items-center">
                <div class="col-12">
                  <div class="col-icon mx-auto">
                    <div class="icon-big text-center icon-success bubble-shadow-small">
                      <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                  </div>
                </div>
                <div class="col col-stats ml-3 ml-sm-0">
                  <div class="numbers mx-auto text-center">
                    <h2 class="card-title mt-2 mb-4 text-uppercase">{{ __('Online Event') }}</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      <div class="col-lg-6">
        <a href="{{ route('add.event.event', ['type' => 'venue']) }}" class="d-block text-decoration-none">
          <div class="card card-stats card-round p-50">
            <div class="card-body ">
              <div class="row align-items-center">
                <div class="col-12">
                  <div class="col-icon mx-auto">
                    <div class="icon-big text-center icon-warning bubble-shadow-small">
                      <i class="fas fa-map-marker-alt"></i>
                    </div>
                  </div>
                </div>
                <div class="col col-stats ml-3 ml-sm-0">
                  <div class="numbers mx-auto text-center">
                    <h2 class="card-title mt-2 mb-4 text-uppercase">{{ __('Venue Event') }}</h2>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
@endsection
