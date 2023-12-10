@extends('organizer.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Profile') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('organizer.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Profile') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-12">
              <div class="card-title">{{ __('Edit Profile') }}</div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row ">
            <div class="col-lg-8 mx-auto">
              <div class="alert alert-danger pb-1 dis-none" id="eventErrors">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>

              <form id="eventForm" action="{{ route('organizer.update_profile') }}" method="POST"
                enctype="multipart/form-data">
                @csrf


                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Photo') . '*' }}</label>
                      <br>
                      <div class="thumb-preview">
                        @if (Auth::guard('organizer')->user()->photo != null)
                          <img
                            src="{{ asset('assets/admin/img/organizer-photo/' . Auth::guard('organizer')->user()->photo) }}"
                            alt="..." class="uploaded-img">
                        @else
                          <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                        @endif

                      </div>

                      <div class="mt-3">
                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                          {{ __('Choose Photo') }}
                          <input type="file" class="img-input" name="photo">
                        </div>
                        @if ($errors->has('photo'))
                          <p class="mt-2 mb-0 text-danger">{{ $errors->first('photo') }}</p>
                        @endif
                        <p class="mt-1 mb-0 text-warning em">{{ __('Image Size 300x300') }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Email') . '*' }}</label>
                      <input type="email" class="form-control" name="email"
                        value="{{ Auth::guard('organizer')->user()->email }}">
                      @if ($errors->has('email'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('email') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Phone') }}</label>
                      <input type="tel" class="form-control" name="phone"
                        value="{{ Auth::guard('organizer')->user()->phone }}">
                      @if ($errors->has('phone'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('phone') }}</p>
                      @endif
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Username') . '*' }}</label>
                      <input type="text" class="form-control" name="username"
                        value="{{ Auth::guard('organizer')->user()->username }}">
                      @if ($errors->has('username'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('username') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Facebook') }}</label>
                      <input type="text" class="form-control" name="facebook"
                        value="{{ Auth::guard('organizer')->user()->facebook }}">
                      @if ($errors->has('facebook'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('facebook') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Twitter') }}</label>
                      <input type="text" class="form-control" name="twitter"
                        value="{{ Auth::guard('organizer')->user()->twitter }}">
                      @if ($errors->has('twitter'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('twitter') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-lg-4">
                    <div class="form-group">
                      <label>{{ __('Linkedin') }}</label>
                      <input type="text" class="form-control" name="linkedin"
                        value="{{ Auth::guard('organizer')->user()->linkedin }}">
                      @if ($errors->has('linkedin'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('linkedin') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-lg-12">
                    <div id="accordion" class="mt-3">
                      @foreach ($languages as $language)
                        <div class="version">
                          <div class="version-header" id="heading{{ $language->id }}">
                            <h5 class="mb-0">
                              <button type="button" class="btn btn-link" data-toggle="collapse"
                                data-target="#collapse{{ $language->id }}"
                                aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                aria-controls="collapse{{ $language->id }}">
                                {{ $language->name . __(' Language') }}
                                {{ $language->is_default == 1 ? '('.__('Default').')' : '' }}
                              </button>
                            </h5>
                          </div>

                          @php
                            $organizer_info = App\Models\OrganizerInfo::where('organizer_id', Auth::guard('organizer')->user()->id)
                                ->where('language_id', $language->id)
                                ->first();
                          @endphp

                          <div id="collapse{{ $language->id }}"
                            class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                            aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                            <div class="version-body">
                              <div class="row">
                                <div class="col-lg-4">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('Name') . '*' }}</label>
                                    <input type="text" class="form-control" name="{{ $language->code }}_name"
                                      placeholder="Enter Your Full Name"
                                      value="{{ $organizer_info ? $organizer_info->name : '' }}">
                                    @if ($errors->has("$language->code" . '_name'))
                                      <p class="mt-2 mb-0 text-danger">
                                        {{ $errors->first("$language->code" . '_name') }}</p>
                                    @endif
                                  </div>
                                </div>

                                <div class="col-lg-4">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('Designation') }}</label>
                                    <input type="text" class="form-control"
                                      name="{{ $language->code }}_designation"
                                      value="{{ $organizer_info ? $organizer_info->designation : '' }}">

                                  </div>
                                </div>

                                <div class="col-lg-4">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('Country') }}</label>
                                    <input type="text" class="form-control" name="{{ $language->code }}_country"
                                      value="{{ $organizer_info ? $organizer_info->country : '' }}">
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('City') }}</label>
                                    <input type="text" class="form-control" name="{{ $language->code }}_city"
                                      value="{{ $organizer_info ? $organizer_info->city : '' }}">
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('State') }}</label>
                                    <input type="text" class="form-control" name="{{ $language->code }}_state"
                                      value="{{ $organizer_info ? $organizer_info->state : '' }}">
                                  </div>
                                </div>
                                <div class="col-lg-4">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('Zip Code') }}</label>
                                    <input type="text" class="form-control" name="{{ $language->code }}_zip_code"
                                      value="{{ $organizer_info ? $organizer_info->zip_code : '' }}">
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('Address') }}</label>
                                    <textarea name="{{ $language->code }}_address" class="form-control">{{ $organizer_info ? $organizer_info->address : '' }}</textarea>
                                  </div>
                                </div>
                                <div class="col-lg-12">
                                  <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                    <label>{{ __('Details') }}</label>
                                    <textarea name="{{ $language->code }}_details" rows="5" class="form-control">{{ $organizer_info ? $organizer_info->details : '' }}</textarea>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col">
                                  @php $currLang = $language; @endphp

                                  @foreach ($languages as $language)
                                    @continue($language->id == $currLang->id)

                                    <div class="form-check py-0">
                                      <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox"
                                          onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                        <span class="form-check-sign">{{ __('Clone for') }} <strong
                                            class="text-capitalize text-secondary">{{ $language->name }}</strong>
                                          {{ __('language') }}</span>
                                      </label>
                                    </div>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
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
              <button type="submit" id="EventSubmit" class="btn btn-success">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
