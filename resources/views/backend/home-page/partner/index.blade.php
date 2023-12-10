@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Partner Section') }}</h4>
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
        <a href="#">{{ __('Partner Section') }}</a>
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
                <div class="card-title">{{ __('Partner Section') }}</div>
              </div>
            </div>
            <div class="col-lg-2 mx-auto">
              @includeIf('backend.partials.languages')
            </div>
          </div>


          <div class="card-body">
            <div class="row">
              <div class="col-lg-6 offset-lg-3">
                <form id="featureForm" action="{{ route('admin.home_page.update_partner_section') }}" method="POST"
                  enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="language_code" value="{{ request()->input('language') }}">
                  <div class="form-group">
                    <label for="">{{ __('Title') }}</label>
                    <input type="text" class="form-control" name="title"
                      value="{{ empty($data->title) ? '' : $data->title }}" placeholder="{{ __('Enter Partner Section Title') }}">
                    @error('title')
                      <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label for="">{{ __('Text') }}</label>
                    <input type="text" class="form-control" name="text"
                      value="{{ empty($data->text) ? '' : $data->text }}" placeholder="{{ __('Enter  Partner Section Text') }}">
                    @error('text')
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
            <div class="col-lg-8">
              <div class="card-title">{{ __('Partners') }}</div>
            </div>

            <div class="col-lg-4  mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                {{ __('Add Partner') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.home_page.bulk_delete_how_work_item') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col">
              @if (count($partners) == 0)
                <h3 class="text-center mt-2">{{ __('NO FEATURE FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>

                        @if ($themeInfo->theme_version == 3)
                          <th scope="col">{{ __('Icon') }}</th>
                        @endif

                        <th scope="col">{{ __('Logo') }}</th>
                        <th scope="col">{{ __('Url') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($partners as $feature)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $feature->id }}">
                          </td>
                          <td>
                            <img src="{{ asset('assets/admin/img/partner/' . $feature->image) }}" alt="client"
                              width="50">
                          </td>
                          <td>{{ $feature->url }}</td>
                          <td>{{ $feature->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary mt-1 btn-xs mr-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editModal" data-id="{{ $feature->id }}"
                              data-image="{{ asset('assets/admin/img/partner/' . $feature->image) }}"
                              data-url="{{ $feature->url }}" data-serial_number="{{ $feature->serial_number }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.home_page.delete_partner', ['id' => $feature->id]) }}"
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
  @include('backend.home-page.partner.create')

  {{-- edit modal --}}
  @include('backend.home-page.partner.edit')
@endsection
