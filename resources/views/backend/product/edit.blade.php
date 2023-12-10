@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Edit Product') }}</h4>
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
        <a
          href="{{ route('admin.shop_management.products', ['language' => $defaultLang->code]) }}">{{ __('Products') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Edit Product') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Edit Product') }}</div>
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
                <label for="" class="mb-2"><strong>{{ __('Gallery Images ') . ' **' }}</strong></label>

                <div class="row">
                  <div class="col-12">
                    <table class="table" id="img-table">

                    </table>
                  </div>
                </div>
                <form action="{{ route('admin.shop_management.product.imgstore') }}" id="my-dropzone"
                  enctype="multipart/formdata" class="dropzone create">
                  @csrf
                  <div class="fallback">
                    <input name="file" type="file" multiple />
                  </div>
                </form>
                <p class="em text-danger mb-0" id="errslider_images"></p>
              </div>
              <form id="eventForm" action="{{ route('admin.shop_management.product.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $product->type }}">
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="form-group">
                  <label for="">{{ __('Feature Image') . '*' }}</label>
                  <br>
                  <div class="thumb-preview">
                    @if ($product->feature_image)
                      <img src="{{ asset('assets/admin/img/product/feature_image/' . $product->feature_image) }}"
                        alt="..." class="uploaded-img">
                    @else
                      <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="uploaded-img">
                    @endif


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
                        value="{{ $product->sku != null ? $product->sku : mt_rand(10000000, 99999999) }}"
                        class="form-control">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Current Price') . '*' }}</label>
                      <input type="number" name="current_price" value="{{ $product->current_price }}"
                        placeholder="{{ __('Enter Current Price') }}" class="form-control">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Previous Price') }}</label>
                      <input type="number" name="previous_price" value="{{ $product->previous_price }}"
                        placeholder="Enter Previous Price" class="form-control">
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Status') . '*' }}</label>
                      <select name="status" class="form-control">
                        <option selected disabled>{{ __('Select a Status') }}</option>
                        <option {{ $product->status == 1 ? 'selected' : '' }} value="1">
                          {{ __('Active') }}
                        </option>
                        <option {{ $product->status == 0 ? 'selected' : '' }} value="0">
                          {{ __('Deactive') }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Is Feature') . '*' }}</label>
                      <select name="is_feature" class="form-control">
                        <option selected disabled>{{ __('Select') }}</option>
                        <option {{ $product->is_feature == 'yes' ? 'selected' : '' }} value="yes">
                          {{ __('Yes') }}
                        </option>
                        <option {{ $product->is_feature == 'no' ? 'selected' : '' }} value="no">{{ __('No') }}
                        </option>
                      </select>
                    </div>
                  </div>


                  <div class="col-lg-6">
                    @if ($product->type == 'physical')
                      <div class="form-group">
                        <label for="">{{ __('Stock Product') . ' **' }} </label>
                        <input type="number" class="form-control ltr" name="stock" value="{{ $product->stock }}"
                          placeholder="{{ __('Enter Product Stock') }}">
                        <p id="errstock" class="mb-0 text-danger em"></p>
                      </div>
                    @endif
                    @if ($product->type == 'digital')
                      <div class="form-group">
                        <label for="">{{ __('Type') . ' **' }} </label>
                        <select name="file_type" class="form-control" id="fileType">
                          <option {{ $product->file_type == 'upload' ? 'selected' : '' }} value="upload" selected>
                            {{ __('File Upload') }}</option>
                          <option {{ $product->file_type == 'link' ? 'selected' : '' }} value="link">
                            {{ __('File Download Link') }}
                          </option>
                        </select>
                        <p id="errfile_type" class="mb-0 text-danger em"></p>
                      </div>
                    @endif
                  </div>
                  @if ($product->type == 'digital')
                    <div class="col-lg-6">
                      <div id="downloadFile" class="form-group {{ $product->file_type == 'upload' ? '' : 'd-none' }}">
                        <label for="">{{ __('Downloadable File') . ' **' }} </label>
                        <br>
                        <input name="download_file" type="file">

                        <p class="mb-0 text-warning">{{ __('Only zip file is allowed') }}</p>
                        <p id="errdownload_file" class="mb-0 text-danger em">
                          {{ __('File Name') . ' :' }}
                          {{ $product->download_file }}
                        </p>
                      </div>

                      <div id="downloadLink" class="form-group {{ $product->file_type == 'link' ? '' : 'd-none' }} ">
                        <label for="">{{ __('Downloadable Link') . ' **' }} </label>
                        <input name="download_link" type="text" value="{{ $product->download_link }}"
                          class="form-control">
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
                            {{ $language->name . __(' Language') }}
                            {{ $language->is_default == 1 ? '(Default)' : '' }}
                          </button>
                        </h5>
                      </div>

                      @php
                        $product_content = DB::table('product_contents')
                            ->where('language_id', $language->id)
                            ->where('product_id', $product->id)
                            ->first();
                      @endphp

                      <div id="collapse{{ $language->id }}"
                        class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                        aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                        <div class="version-body">
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Title') . '*' }}</label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  value="{{ @$product_content->title }}" placeholder="Enter  title">
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
                                    <option @selected(@$product_content->category_id == $category->id) value="{{ $category->id }}">
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
                                  placeholder="{{ __('Enter Product Summary') }}">{{ @$product_content->summary }}</textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Description') . '*' }}</label>
                                <textarea id="descriptionTmce{{ $language->id }}" class="form-control summernote"
                                  name="{{ $language->code }}_description" placeholder="{{ __('Enter Product Description') }}" data-height="300">{!! @$product_content->description !!}</textarea>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label for="">{{ __('Tags') }} </label>
                                <input type="text" class="form-control" name="{{ $language->code }}_tags"
                                  value="{{ @$product_content->tags }}" data-role="tagsinput"
                                  placeholder="{{ __('Enter tags') }}">
                                <p id="errtags" class="mb-0 text-danger em"></p>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Keywords') }}</label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  value="{{ @$product_content->meta_keywords }}"
                                  placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="form-group {{ $language->direction == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Meta Description') }}</label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="{{ __('Enter Meta Description') }}">{{ @$product_content->meta_description }}</textarea>
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
                {{ __('Update') }}
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

    var rmvdbUrl = "{{ route('admin.shop_management.imgdbrmv') }}";
    var ProductloadImgs = "{{ route('admin.shop_management.product.images', $product->id) }}";
  </script>
@endsection
