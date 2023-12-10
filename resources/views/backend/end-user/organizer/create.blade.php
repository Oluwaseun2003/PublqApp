@extends('backend.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Add Organizer') }}</h4>
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
                <a href="#">{{ __('Add Organizer') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title">{{ __('Add Organizer') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="alert alert-danger pb-1 dis-none" id="eventErrors">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <ul></ul>
                            </div>

                            <form id="eventForm" action="{{ route('admin.organizer_management.save-organizer') }}"
                                method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="">{{ __('Photo') . '*' }}</label>
                                            <br>
                                            <div class="thumb-preview">
                                                <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                                    class="uploaded-img">
                                            </div>
                                            <div class="mt-3">
                                                <div role="button" class="btn btn-primary btn-sm upload-btn">
                                                    {{ __('Choose Photo') }}
                                                    <input type="file" class="img-input" name="photo">
                                                </div>
                                                <p class="mt-1 mb-0 text-warning em">{{ __('Image Size 300x300') }}</p>
                                                <p id="editErr_photo" class="mt-1 mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Email') . ' *' }}</label>
                                            <input type="text" value="" class="form-control" name="email"
                                                placeholder="{{ __('Enter Email') }}">
                                            <p id="editErr_email" class="mt-1 mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Phone') }}</label>
                                            <input type="text" value="" class="form-control" name="phone"
                                                placeholder="{{ __('Enter Phone') }}">
                                            <p id="editErr_phone" class="mt-1 mb-0 text-danger em"></p>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Username') . ' *' }}</label>
                                            <input type="text" value="" class="form-control" name="username"
                                                placeholder="{{ __('Enter Username') }}">
                                            <p id="editErr_username" class="mt-1 mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ __('Password') }}</label>
                                            <input type="password" value="" class="form-control" name="password"
                                                placeholder="{{ __('Enter Password') }} ">
                                            <p id="editErr_password" class="mt-1 mb-0 text-danger em"></p>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ __('Facebook') }}</label>
                                            <input type="text" class="form-control" name="facebook" value="">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ __('Twitter') }}</label>
                                            <input type="text" class="form-control" name="twitter" value="">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>{{ __('Linkedin') }}</label>
                                            <input type="text" class="form-control" name="linkedin" value="">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div id="accordion" class="mt-3">
                                            @foreach ($languages as $language)
                                                <div class="version">
                                                    <div class="version-header" id="heading{{ $language->id }}">
                                                        <h5 class="mb-0">
                                                            <button type="button" class="btn btn-link"
                                                                data-toggle="collapse"
                                                                data-target="#collapse{{ $language->id }}"
                                                                aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                                                aria-controls="collapse{{ $language->id }}">
                                                                {{ $language->name . __(' Language') }}
                                                                {{ $language->is_default == 1 ? '(' . __('Default') . ')' : '' }}
                                                            </button>
                                                        </h5>
                                                    </div>


                                                    <div id="collapse{{ $language->id }}"
                                                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                                                        aria-labelledby="heading{{ $language->id }}"
                                                        data-parent="#accordion">
                                                        <div class="version-body">
                                                            <div class="row">
                                                                <div class="col-lg-4">
                                                                    <div
                                                                        class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                                                        <label>{{ __('Name') . '*' }}</label>
                                                                        <input type="text" class="form-control"
                                                                            name="{{ $language->code }}_name"
                                                                            placeholder="Enter Your Full Name"
                                                                            value="">
                                                                        @if ($errors->has("$language->code" . '_name'))
                                                                            <p class="mt-2 mb-0 text-danger">
                                                                                {{ $errors->first("$language->code" . '_name') }}
                                                                            </p>
                                                                        @endif

                                                                        <p id="editErr_{{ $language->code }}_name"
                                                                            class="mt-1 mb-0 text-danger em"></p>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label>{{ __('Designation') }}</label>
                                                                        <input type="text" class="form-control"
                                                                            name="{{ $language->code }}_designation"
                                                                            value="">

                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label>{{ __('Country') }}</label>
                                                                        <input type="text" class="form-control"
                                                                            name="{{ $language->code }}_country"
                                                                            value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label>{{ __('City') }}</label>
                                                                        <input type="text" class="form-control"
                                                                            name="{{ $language->code }}_city"
                                                                            value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label>{{ __('State') }}</label>
                                                                        <input type="text" class="form-control"
                                                                            name="{{ $language->code }}_state"
                                                                            value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-4">
                                                                    <div class="form-group">
                                                                        <label>{{ __('Zip Code') }}</label>
                                                                        <input type="text" class="form-control"
                                                                            name="{{ $language->code }}_zip_code"
                                                                            value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label>{{ __('Address') }}</label>
                                                                        <textarea name="{{ $language->code }}_address" class="form-control"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12">
                                                                    <div class="form-group">
                                                                        <label>{{ __('Details') }}</label>
                                                                        <textarea name="{{ $language->code }}_details" rows="5" class="form-control"></textarea>
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
                                                                                <input class="form-check-input"
                                                                                    type="checkbox"
                                                                                    onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $language->id }}', event)">
                                                                                <span
                                                                                    class="form-check-sign">{{ __('Clone for') }}
                                                                                    <strong
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
                                {{ __('Submit') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
