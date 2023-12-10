@extends('organizer.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Verify Email') }}</h4>
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
                <a href="#">{{ __('Verify Email') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-title">{{ __('Verify Email') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxEditForm" action="{{ route('organizer.send.verify.link') }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label>{{ __('Email' . ' *') }}</label>
                                    <input type="email" readonly value="{{ Auth::guard('organizer')->user()->email }}"
                                        class="form-control" name="current_password">
                                    <p id="editErr_current_password" class="mt-1 mb-0 text-danger em"></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" id="updateBtn" class="btn btn-success">
                                {{ __('Send Verification Link') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
