@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Footer Content') }}</h4>
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
                <a href="#">{{ __('Footer Content') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-title">{{ __('Update Footer Content') }}</div>
                        </div>

                        <div class="col-lg-2">
                            @includeIf('backend.partials.languages')
                        </div>
                    </div>
                </div>

                <div class="card-body pt-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxForm"
                                action="{{ route('admin.footer.update_content', ['language' => request()->input('language')]) }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">{{ __('Footer Logo') . '*' }}</label>
                                    <br>
                                    <div class="thumb-preview">
                                        @if ($data)
                                            @if ($data->footer_logo == null)
                                                <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                                    class="uploaded-img">
                                            @else
                                                <img src="{{ asset('assets/admin/img/footer_logo/' . $data->footer_logo) }}"
                                                    alt="..." class="uploaded-img">
                                            @endif
                                        @else
                                            <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                                class="uploaded-img">
                                        @endif


                                    </div>

                                    <div class="mt-3">
                                        <div role="button" class="btn btn-primary btn-sm upload-btn">
                                            {{ __('Choose Image') }}
                                            <input type="file" class="img-input" name="footer_logo">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Footer Background Color') . '*' }}</label>
                                    <input class="jscolor form-control ltr" name="footer_background_color"
                                        value="{{ !is_null($data) ? $data->footer_background_color : '' }}">
                                    <p id="err_footer_background_color" class="em text-danger mt-2 mb-0"></p>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('About Company') . '*' }}</label>
                                    <textarea id="descriptionTmce1" class="form-control summernote" name="about_company" rows="5" cols="80">{!! !is_null($data) ? $data->about_company : '' !!}</textarea>
                                    <p id="err_about_company" class="em text-danger mt-2 mb-0"></p>
                                </div>

                                <div class="form-group">
                                    <label>{{ __('Copyright Text') . '*' }}</label>
                                    <textarea id="copyrightSummernote" class="form-control summernote" name="copyright_text" data-height="80">{{ !is_null($data) ? $data->copyright_text : '' }}</textarea>
                                    <p id="err_copyright_text" class="em text-danger mt-2 mb-0"></p>
                                    <p class="text-warning"> {{ __('Note: {year} will be replaced by the current year') }}
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" id="submitBtn" class="btn btn-success">
                                {{ __('Update') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
