@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Testimonials Section') }}</h4>
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
        <a href="#">{{ __('Testimonials Section') }}</a>
      </li>
    </ul>
  </div>

  @if ($themeInfo->theme_version == 1)
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">
                <div class="card-title">{{ __('Testimonial Section') }}</div>
              </div>
            </div>
            <div class="col-lg-2 mx-auto">
              @includeIf('backend.partials.languages')
            </div>
          </div>


          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <form id="featureForm" action="{{ route('admin.home_page.update_testimonial_section_image') }}"
                  method="POST" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="language_code" value="{{ request()->input('language') }}">
                  <div class="form-group">
                    <label for="">{{ __('Title') }}</label>
                    <input type="text" class="form-control" name="title"
                      value="{{ empty($data->title) ? '' : $data->title }}" placeholder="{{ __('Enter Testimonial Title') }}">
                    @error('title')
                      <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="">{{ __('Text') }}</label>
                    <input type="text" class="form-control" name="text"
                      value="{{ empty($data->text) ? '' : $data->text }}" placeholder="{{ __('Enter Testimonial Text') }}">
                    @error('text')
                      <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="">{{ __('Image') }}</label>
                    <br>
                    <div class="thumb-preview">
                      @if (!empty($data->image))
                        <img src="{{ asset('assets/admin/img/testimonial/' . $data->image) }}" alt="image"
                          class="uploaded-img">
                      @else
                        <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
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
                    <label for="">{{ __('Review Text') }}</label>
                    <input type="text" class="form-control" name="review_text"
                      value="{{ empty($data->review_text) ? '' : $data->review_text }}" placeholder="{{ __('Enter Review Text') }}">
                    @error('review_text')
                      <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" form="featureForm" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('Testimonials') }}</div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.home_page.bulk_delete_testimonial') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col">
              @if (count($testimonials) == 0)
                <h3 class="text-center mt-2">{{ __('NO TESTIMONIAL FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Image') }}</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Occupation') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($testimonials as $testimonial)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $testimonial->id }}">
                          </td>
                          <td>
                            <img src="{{ asset('assets/admin/img/clients/' . $testimonial->image) }}" alt="client"
                              width="50">
                          </td>
                          <td>{{ $testimonial->name }}</td>
                          <td>{{ $testimonial->occupation }}</td>
                          <td>{{ $testimonial->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary mt-1 btn-xs mr-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editModal" data-id="{{ $testimonial->id }}"
                              data-image="{{ asset('assets/admin/img/clients/' . $testimonial->image) }}"
                              data-name="{{ $testimonial->name }}" data-occupation="{{ $testimonial->occupation }}"
                              data-comment="{{ $testimonial->comment }}"
                              data-serial_number="{{ $testimonial->serial_number }}"
                              data-rating="{{ $testimonial->rating }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.home_page.delete_testimonial', ['id' => $testimonial->id]) }}"
                              method="post">

                              @csrf
                              <button type="submit" class="btn btn-danger mt-1 btn-xs deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="card-footer"></div>
      </div>
    </div>
  </div>

  {{-- create modal --}}
  @include('backend.home-page.testimonial-section.create')

  {{-- edit modal --}}
  @include('backend.home-page.testimonial-section.edit')
@endsection
