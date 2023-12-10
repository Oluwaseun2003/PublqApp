@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Product') }}</h4>
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
        <a href="#">{{ __('Shop Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Manage Products') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('admin.shop_management.product_type') }}">{{ __('Product Type') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Product') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add Product') }}</div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('admin.shop_management.products', ['language' => $defaultLang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <div class="alert alert-danger pb-1 dis-none" id="eventErrors">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <ul></ul>
              </div>
              <div class="col-lg-12">
                <label for="" class="mb-2"><strong>{{ __('Gallery Images') . ' **' }} </strong></label>
                <form action="{{ route('admin.shop_management.product.imgstore') }}" id="my-dropzone"
                  enctype="multipart/formdata" class="dropzone create">
                  @csrf
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                </form>
                <p class="em text-danger mb-0" id="errslider_images"></p>
              </div>
              <form id="eventForm" action="{{ route('admin.shop_management.product.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ request()->input('type') }}">
                <div class="form-group">
                  <label for="">{{ __('Feature Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                  </div>

                  <div class="mt-3">
                    <div role="button" class="btn btn-primary btn-sm upload-btn">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="feature_image">
                    </div>
                  </div>
                </div>

                <div class="row ">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('SKU') . '*' }}</label>
                      <input type="text" name="sku" placeholder="{{ __('Enter Sku') }}"
                        value="{{ mt_rand(10000000, 99999999) }}" class="form-control">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Current Price') . '*' }}</label>
                      <input type="number" name="current_price" placeholder="{{ __('Enter Current Price') }}"
                        class="form-control">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Previous Price') }}</label>
                      <input type="number" name="previous_price" placeholder="{{ __('Enter Previous Price') }}"
                        class="form-control">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Status') . '*' }}</label>
                      <select name="status" class="form-control">
                        <option selected disabled>{{ __('Select a Status') }}</option>
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Deactive') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Is Feature') . '*' }}</label>
                      <select name="is_feature" class="form-control">
                        <option selected disabled>{{ __('Select') }}</option>
                        <option value="yes">{{ __('Yes') }}</option>
                        <option value="no">{{ __('No') }}</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    @if (request()->input('type') == 'physical')
                      <div class="form-group">
                        <label for="">{{ __('Stock Product') }} **</label>
                        <input type="number" class="form-control ltr" name="stock" value=""
                          placeholder="{{ __('Enter Product Stock') }}">
                        <p id="errstock" class="mb-0 text-danger em"></p>
                      </div>
                    @endif
                    @if (request()->input('type') == 'digital')
                      <div class="form-group">
                        <label for="">{{ __('Type') . ' **' }} </label>
                        <select name="file_type" class="form-control" id="fileType">
                          <option value="upload" selected>{{ __('File Upload') }}</option>
                          <option value="link">{{ __('File Download Link') }}</option>
                        </select>
                        <p id="errfile_type" class="mb-0 text-danger em"></p>
                      </div>
                    @endif
                  </div>
                  @if (request()->input('type') == 'digital')
                    <div class="col-lg-6">
                      <div id="downloadFile" class="form-group">
                        <label for="">{{ __('Downloadable File') . ' **' }} </label>
                        <br>
                        <input name="download_file" type="file">
                        <p class="mb-0 text-warning">{{ __('Only zip file is allowed') }}</p>
                        <p id="errdownload_file" class="mb-0 text-danger em"></p>
                      </div>
                      <div id="downloadLink" class="form-group d-none">
                        <label for="">{{ __('Downloadable Link') . ' **' }} </label>
                        <input name="download_link" type="text" class="form-control">
                        <p id="errdownload_link" class="mb-0 text-danger em"></p>
                      </div>
                    </div>
                  @endif
                </div>


                <div id="accordion" class="mt-3">
                  @foreach ($languages as $language)
                    <div class="version">
                      <div class="version-header" id="heading{{ $language->id }}">
                        <h5 class="mb-0">
                          <button type="button" class="btn btn-link" data-toggle="collapse"
                            data-target="#collapse{{ $language->id }}"
                            aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                            aria-controls="collapse{{ $language->id }}">
                            {{ $language->name . ' ' . __('Language') }}
                            {{ $language->is_default == 1 ? '(' . __('Default') . ')' : '' }}
                          </button>
                        </h5>
                      </div>

                      <div id="collapse{{ $language->id }}"
                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body">
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Title') . '*' }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="{{ __('Enter Title') }}">
                              </div>
                            </div>

                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                @php
                                  $categories = DB::table('product_categories')
                                      ->where('language_id', $language->id)
                                      ->where('status', 1)
                                      ->orderByDesc('id')
                                      ->get();
                                @endphp

                                <label for="">{{ __('Category') . '*' }}</label>
                                <select name="{{ $language->code }}_category_id" class="form-control">
                                  <option selected disabled>{{ __('Select Category') }}
                                  </option>

                                  @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                      {{ $category->name }}</option>
                                  @endforeach
                                </select>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label for="summary">{{ __('Summary') }} </label>
                                <textarea name="{{ $language->code }}_summary" id="summary" class="form-control" rows="4"
                                  placeholder="{{ __('Enter Product Summary') }}"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Description') . '*' }}</label>
                                <textarea id="descriptionTmce{{ $language->id }}" class="form-control summernote"
                                  name="{{ $language->code }}_description" placeholder="{{ __('Enter Product Description') }}" data-height="300"></textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label for="">{{ __('Tags') }} </label>
                                <input type="text" class="form-control" name="{{ $language->code }}_tags"
                                  value="" data-role="tagsinput" placeholder="{{ __('Enter Tags') }}">
                                <p id="errtags" class="mb-0 text-danger em"></p>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords" value=""
                                  placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="{{ __('Enter Meta Description') }}"></textarea>
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
                                    <span class="form-check-sign">{{ __('Clone for') }}
                                      <strong class="text-capitalize text-secondary">{{ $language->name }}</strong>
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
                <div id="sliders"></div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="EventSubmit" class="btn btn-success">
                {{ __('Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @php
    $languages = App\Models\Language::get();
  @endphp
  <script>
    let languages = "{{ $languages }}";
  </script>
  <script type="text/javascript" src="{{ asset('assets/admin/js/admin-partial.js') }}"></script>
  <script src="{{ asset('assets/admin/js/admin_dropzone.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('.js-example-basic-single').select2();
    });
  </script>
@endsection

@section('variables')
  <script>
    "use strict";
    var storeUrl = "{{ route('admin.shop_management.product.imgstore') }}";
    var removeUrl = "{{ route('admin.shop_management.product.imgrmv') }}";
  </script>
@endsection
