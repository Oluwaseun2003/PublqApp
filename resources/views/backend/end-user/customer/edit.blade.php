@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Customer') }}</h4>
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
        <a
          href="{{ route('admin.organizer_management.registered_customer', ['language' => $defaultLang->code]) }}">{{ __('Registered Customers') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Customer') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">

              <div class="row">
                <div class="col-lg-8">
                  <div class="card-title">{{ __('Edit Customer') }}</div>
                </div>
                <div class="col-lg-4">
                  <a class="btn btn-info btn-sm float-right d-inline-block mr-2"
                    href="{{ route('admin.organizer_management.registered_customer', ['language' => $defaultLang->code]) }}">
                    <span class="btn-label">
                      <i class="fas fa-backward"></i>
                    </span>
                    {{ __('Back') }}
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 mx-auto">
              <form id="ajaxEditForm"
                action="{{ route('admin.customer_management.customer.update_customer', ['id' => $customer->id]) }}"
                method="post">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') . '*' }}</label>
                      <br>
                      <div class="thumb-preview">
                        @if ($customer->photo != null)
                          <img src="{{ asset('assets/admin/img/customer-profile/' . $customer->photo) }}" alt="..."
                            class="uploaded-img">
                        @else
                          <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                        @endif

                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="photo">
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('First Name')." *" }}</label>
                      <input type="text" value="{{ $customer->fname }}" class="form-control" name="fname">
                      <p id="editErr_fname" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Last Name'). " *" }}</label>
                      <input type="text" value="{{ $customer->lname }}" class="form-control" name="lname">
                      <p id="editErr_lname" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Email')." *" }}</label>
                      <input type="text" value="{{ $customer->email }}" class="form-control" name="email">
                      <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Username')." *" }}</label>
                      <input type="text" value="{{ $customer->username }}" class="form-control" name="username">
                      <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Phone') }}</label>
                      <input type="tel" value="{{ $customer->phone }}" class="form-control" name="phone">
                      <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Country') }}</label>
                      <input type="text" value="{{ $customer->country }}" class="form-control" name="country">
                      <p id="editErr_country" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('City') }}</label>
                      <input type="text" value="{{ $customer->city }}" class="form-control" name="city">
                      <p id="editErr_city" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('State') }}</label>
                      <input type="text" value="{{ $customer->state }}" class="form-control" name="state">
                      <p id="editErr_state" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Zip Code') }}</label>
                      <input type="text" value="{{ $customer->zip_code }}" class="form-control" name="zip_code">
                      <p id="editErr_zip_code" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div class="form-group">
                      <label>{{ __('Address') }}</label>
                      <textarea name="address" class="form-control">{{ $customer->address }}</textarea>
                      <p id="editErr_address" class="mt-1 mb-0 text-danger em"></p>
                    </div>
                  </div>

                </div>
              </form>
            </div>
            <div class="col-lg-6">

            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="updateBtn" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
