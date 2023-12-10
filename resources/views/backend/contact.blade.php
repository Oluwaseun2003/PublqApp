@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Contact Page') }}</h4>
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
        <a href="#">{{ __('Footer') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Contact Page') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-9">
              <div class="card-title d-inline-block">
                {{ __('Contact Page') }}
              </div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">

              @if (session()->has('course_status_warning'))
                <div class="alert alert-warning">
                  <p class="text-dark mb-0">{{ session()->get('course_status_warning') }}</p>
                </div>
              @endif
              <div class="row">
                <div class="col-md-8 mx-auto">
                  <form id="ajaxForm" class="create"
                    action="{{ route('admin.update.contact_page', request()->input('language')) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label>{{ __('Form Title') . '*' }}</label>
                          <input type="text" class="form-control" value="{{ @$abs->contact_form_title }}"
                            name="contact_form_title" placeholder="{{ __('Enter Title') }}">
                          <p id="err_contact_form_title" class="mt-2 mb-0 text-danger em"></p>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label>{{ __('Form Subtitle') . '*' }}</label>
                          <input type="text" class="form-control" name="contact_form_subtitle"
                            value="{{ @$abs->contact_form_title }}" placeholder="{{ __('Enter Form Subtitle') }}">
                          <p id="err_contact_form_subtitle" class="mt-2 mb-0 text-danger em"></p>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label>{{ __('Address') . ' *' }} </label>
                          <textarea class="form-control" name="contact_addresses" rows="3">{{ @$abs->contact_addresses }}</textarea>
                          <p class="mb-0 text-warning">
                            {{ __('Use newline to seperate multiple addresses') }}</p>
                          <p id="err_contact_addresses" class="mb-0 text-danger em"></p>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label>{{ __('Phone') . ' *' }} </label>
                          <input class="form-control" name="contact_numbers" data-role="tagsinput"
                            value="{{ @$abs->contact_numbers }}" placeholder="{{ __('Enter Phone Number') }}">
                          <p class="mb-0 text-warning">
                            {{ __('Use comma (,) to seperate multiple contact numbers') }}
                          </p>
                          <p id="err_contact_numbers" class="mb-0 text-danger em"></p>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label>{{ __('Email') . ' *' }} </label>
                          <input class="form-control ltr" name="contact_mails" data-role="tagsinput"
                            value="{{ @$abs->contact_mails }}" placeholder="{{ __('Enter Email Address') }}">
                          <p class="mb-0 text-warning">
                            {{ __('Use comma (,) to seperate multiple contact mails') }}
                          </p>
                          <p id="err_contact_mails" class="mb-0 text-danger em"></p>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label>{{ __('Latitude') . '*' }}</label>
                          <input type="text" class="form-control" name="latitude" value="{{ @$abs->latitude }}"
                            placeholder="{{ __('Enter Latitude') }}">
                          <p id="err_latitude" class="mt-2 mb-0 text-danger em"></p>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label>{{ __('Longitude') . '*' }}</label>
                          <input type="text" class="form-control" name="longitude" value="{{ @$abs->longitude }}"
                            placeholder="{{ __('Enter Longitude') }}">
                          <p id="err_longitude" class="mt-2 mb-0 text-danger em"></p>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="form-group">
                          <label>{{ __('Map Zoom') . '*' }}</label>
                          <input type="text" class="form-control" name="map_zoom" value="{{ @$abs->map_zoom }}"
                            placeholder="{{ __('Enter Map Zoom') }}">
                          <p id="err_map_zoom" class="mt-2 mb-0 text-danger em"></p>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-success" id="submitBtn">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
