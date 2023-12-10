@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Section Titles') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{route('admin.dashboard')}}">
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
        <a href="#">{{ __('Section Titles') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.home_page.update_section_title', ['language' => request()->input('language')]) }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-10">
                <div class="card-title">{{ __('Update Section Titles') }}</div>
              </div>

              <div class="col-lg-2">
                @includeIf('backend.partials.languages')
              </div>
            </div>
          </div>

          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                @if ($themeInfo->theme_version == 1)
                  <div class="form-group">
                    <label>{{ __('Event Section Title') }}</label>
                    <input class="form-control" name="event_section_title" value="{{ empty($data->event_section_title) ? '' : $data->event_section_title }}" placeholder="{{ __('Enter Event Section Title') }}">
                  </div>
                @endif

                <div class="form-group">
                  <label>{{ __('Category Section Title') }}</label>
                  <input class="form-control" name="category_section_title" value="{{ empty($data->category_section_title) ? '' : $data->category_section_title }}" placeholder="{{ __('Enter Category Section Title') }}">
                </div>

                @if ($themeInfo->theme_version == 2)
                  <div class="form-group">
                    <label>{{ __('Featured Instructors Section Title') }}</label>
                    <input class="form-control" name="featured_instructors_section_title" value="{{ empty($data->featured_instructors_section_title) ? '' : $data->featured_instructors_section_title }}" placeholder="{{ __('Enter Featured Instructors Section Title') }}">
                  </div>

                  <div class="form-group">
                    <label>{{ __('Testimonials Section Title') }}</label>
                    <input class="form-control" name="testimonials_section_title" value="{{ empty($data->testimonials_section_title) ? '' : $data->testimonials_section_title }}" placeholder="{{ __('Enter Testimonials Section Title') }}">
                  </div>
                @endif

                @if ($themeInfo->theme_version == 3)
                  <div class="form-group">
                    <label>{{ __('Features Section Title') }}</label>
                    <input class="form-control" name="features_section_title" value="{{ empty($data->features_section_title) ? '' : $data->features_section_title }}" placeholder="{{ __('Enter Features Section Title') }}">
                  </div>

                  <div class="form-group">
                    <label>{{ __('Blog Section Title') }}</label>
                    <input class="form-control" name="blog_section_title" value="{{ empty($data->blog_section_title) ? '' : $data->blog_section_title }}" placeholder="{{ __('Enter Blog Section Title') }}">
                  </div>
                @endif
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
