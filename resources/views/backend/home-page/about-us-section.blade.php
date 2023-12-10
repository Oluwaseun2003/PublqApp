@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('About Us Section') }}</h4>
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
                <a href="#">{{ __('Home Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('About Us Section') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-title">{{ __('Update About Us Section') }}</div>
                        </div>

                        <div class="col-lg-2">
                            @includeIf('backend.partials.languages')
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="aboutUsForm"
                                action="{{ route('admin.home_page.update_about_us_section', ['language' => request()->input('language')]) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{ __('Image') . '*' }}</label>
                                    <br>
                                    <div class="thumb-preview">
                                        @if (!empty($data->image))
                                            <img src="{{ asset('assets/admin/img/about-us-section/' . $data->image) }}"
                                                alt="image" class="uploaded-img">
                                        @else
                                            <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                                class="uploaded-img">
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                                            {{ __('Choose Image') }}
                                            <input type="file" class="img-input" name="image">
                                        </div>
                                    </div>
                                    @error('image')
                                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="">{{ __('Title') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        value="{{ empty($data->title) ? '' : $data->title }}"
                                        placeholder="{{ __('Enter About Us Section Title') }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ __('Subtitle') }}</label>
                                    <input type="text" class="form-control" name="subtitle"
                                        value="{{ empty($data->subtitle) ? '' : $data->subtitle }}"
                                        placeholder="{{ __('Enter About Us Section Subtitle') }}">
                                </div>

                                <div class="form-group">
                                    <label for="">{{ __('Text') }}</label>
                                    <textarea id="descriptionTmce1" class="form-control summernote" name="text"
                                        placeholder="{{ __('Enter About Us Section Text') }}" rows="5">{{ empty($data->text) ? '' : $data->text }}</textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" form="aboutUsForm" class="btn btn-success">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
