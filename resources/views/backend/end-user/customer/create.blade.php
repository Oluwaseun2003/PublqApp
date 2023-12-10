@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Customer') }}</h4>
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
        <a href="#">{{ __('Customers Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.organizer_management.registered_customer') }}">{{ __('Registered Customers') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Customer') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Add Customer') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-9 mx-auto">
              <form id="ajaxEditForm" action="{{ route('admin.organizer_management.store_customer') }}" method="post">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') . '*' }}</label>
                      <br>
                      <div class="thumb-preview">
                        <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                      </div>
                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Photo') }}
                          <input type="file" class="img-input" name="photo">
                        </div>
                        <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('First Name')." *" }}</label>
                      <input type="text" value="" class="form-control" name="fname"
                        placeholder="{{ __('Enter First Name') }}">
                      <p id="editErr_fname" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Enter Last Name')." *" }}</label>
                      <input type="text" value="" class="form-control" name="lname"
                        placeholder="{{ __('Enter Last Name') }}">
                      <p id="editErr_lname" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Email'). " *" }}</label>
                      <input type="text" value="" class="form-control" name="email"
                        placeholder="{{ __('Enter Email Address') }}">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Username')." *" }}</label>
                      <input type="text" value="" class="form-control" name="username"
                        placeholder="{{ __('Enter Username') }}">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Phone') }}</label>
                      <input type="text" value="" class="form-control" name="phone"
                        placeholder="{{ __('Enter Phone Number') }}">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Country') }}</label>
                      <input type="text" value="" class="form-control" name="country"
                        placeholder="{{ __("Enter Country") }}">
                      <p id="editErr_country" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('City') }}</label>
                      <input type="text" value="" class="form-control" name="city"
                        placeholder="{{ __('Enter City') }}">
                      <p id="editErr_city" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('State') }}</label>
                      <input type="text" value="" class="form-control" name="state"
                        placeholder="{{ __('Enter State') }}">
                      <p id="editErr_state" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Zip Code') }}</label>
                      <input type="text" value="" class="form-control" name="zip_code"
                        placeholder="{{ __('Enter Zip Code') }}">
                      <p id="editErr_zip_code" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Password')." *" }} </label>
                      <input type="password" value="" class="form-control" name="password"
                        placeholder="{{ __('Enter Password') }} ">
                      <p id="editErr_password" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Confirm Password') }} *</label>
                      <input type="password" value="" class="form-control" name="password_confirmation"
                        placeholder="{{ __('Enter Confirm Password') }} ">
                      <p id="editErr_password_confirmation" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Address') }}</label>
                      <textarea name="address" class="form-control" placeholder="{{ __('Enter Address') }}"></textarea>
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
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
              <button type="submit" id="updateBtn" class="btn btn-success">
                {{ __('Submit') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
