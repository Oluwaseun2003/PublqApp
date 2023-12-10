@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Setting') }}</h4>
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
        <a href="#">{{ __('Shop Management') }}</a>
      </li>

      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Setting') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Setting') }}</div>

        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <form id="eventForm" action="{{ route('admin.product.setting.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-10 mx-auto">
                    <div class="form-group mt-1">
                      <label for="">{{ __('Shop') . '*' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="shop_status" {{ $abex->shop_status == 1 ? 'checked':'' }} value="1" class="selectgroup-input" >
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="shop_status" {{ $abex->shop_status == 0 ? 'checked':'' }} value="0" class="selectgroup-input">
                          <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                      </div>
                      <p class="text-warning mb-0">{{ __('By enabling / disabling, you can completely enable / disable the relevant pages of your shop in this system') }}</p>
                    </div>

                    <div class="form-group mt-1">
                      <label for="">{{ __('Catalog Mode') . '*' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="catalog_mode" {{ $abex->catalog_mode == 1 ? 'checked':'' }} value="1" class="selectgroup-input" >
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="catalog_mode" {{ $abex->catalog_mode == 0 ? 'checked':'' }} value="0" class="selectgroup-input">
                          <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                      </div>
                      <p class="text-warning mb-0">{{ __('If you enable catalog mode, then pricing, cart, checkout option of products will be removed. But product & product details page will remain') }}</p>
                    </div>

                    <div class="form-group mt-1">
                      <label for="">{{ __('Rating System') . ' *' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="is_shop_rating" {{ $abex->is_shop_rating == 1 ? 'checked':'' }} value="1" class="selectgroup-input" >
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="is_shop_rating" {{ $abex->is_shop_rating == 0 ? 'checked':'' }} value="0" class="selectgroup-input">
                          <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                      </div>
                    </div>

                    <div class="form-group mt-1">
                      <label for="">{{ __('Guest Checkout') . ' *' }}</label>
                      <div class="selectgroup w-100">
                        <label class="selectgroup-item">
                          <input type="radio" name="shop_guest_checkout" {{ $abex->shop_guest_checkout == 1 ? 'checked':'' }} value="1" class="selectgroup-input" >
                          <span class="selectgroup-button">{{ __('Active') }}</span>
                        </label>

                        <label class="selectgroup-item">
                          <input type="radio" name="shop_guest_checkout" {{ $abex->shop_guest_checkout == 0 ? 'checked':'' }} value="0" class="selectgroup-input">
                          <span class="selectgroup-button">{{ __('Deactive') }}</span>
                        </label>
                      </div>
                    </div>

                    <div class="form-group mt-1">
                      <label for="">{{ __('Tax') . '*' }}</label>
                      <input type="text" name="shop_tax" value="{{ $abex->shop_tax }}" placeholder="{{ __('Enter Tax') }}" class="form-control">
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



