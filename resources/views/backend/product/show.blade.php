@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Products') }}</h4>
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
        <a href="#">{{ __('Products') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">
                {{ __('Products') . ' (' . $language->name . ' ' . __('Language') . ')' }}
              </div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="{{ route('admin.shop_management.product_type') }}" class="btn btn-secondary btn-sm float-right"> <i
                  class="fas fa-plus"></i>
                {{ __('Add Product') }}
              </a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.shop_management.product.bulk_delete') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">

              @if (session()->has('course_status_warning'))
                <div class="alert alert-warning">
                  <p class="text-dark mb-0">{{ session()->get('course_status_warning') }}</p>
                </div>
              @endif

              @if (count($products) == 0)
                <h3 class="text-center mt-2">{{ __('NO PRODUCTS FOUND FOR') .' '. $language->name . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Price') }}</th>
                        <th scope="col">{{ __('Type') }}</th>
                        <th scope="col">{{ __('Category') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Featured') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($products as $product)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $product->id }}">
                          </td>
                          <td width="20%">
                            {{ $product->title }}
                          </td>
                          <td>{{ $product->current_price }}</td>
                          <td>{{ $product->type }}</td>
                          <td>
                            {{ $product->category }}
                          </td>

                          <td>
                            <form id="statusForm-{{ $product->id }}" class="d-inline-block"
                              action="{{ route('admin.shop_management.product.status_update', ['id' => $product->id, 'language' => request()->input('language')]) }}"
                              method="post">

                              @csrf
                              <select
                                class="form-control form-control-sm {{ $product->status == 0 ? 'bg-warning text-dark' : 'bg-primary' }}"
                                name="status"
                                onchange="document.getElementById('statusForm-{{ $product->id }}').submit()">
                                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>
                                  {{ __('DeActive') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>

                            <form id="featuredForm-{{ $product->id }}" class="d-inline-block"
                              action="{{ route('admin.shop_management.product.update_feature', ['id' => $product->id]) }}"
                              method="post">

                              @csrf
                              <select
                                class="form-control form-control-sm {{ $product->is_feature == 'yes' ? 'bg-success' : 'bg-danger' }}"
                                name="is_feature"
                                onchange="document.getElementById('featuredForm-{{ $product->id }}').submit()">
                                <option value="yes" {{ $product->is_feature == 'yes' ? 'selected' : '' }}>
                                  {{ __('Yes') }}
                                </option>
                                <option value="no" {{ $product->is_feature == 'no' ? 'selected' : '' }}>
                                  {{ __('No') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <a href="{{ route('admin.shop_management.product.edit', ['id' => $product->id]) }}"
                              class="btn btn-primary mt-1 btn-sm">
                              <i class="fas fa-edit"></i>
                            </a>
                            <form class="deleteForm d-block"
                              action="{{ route('admin.shop_management.product.destroy', ['id' => $product->id]) }}"
                              method="post">

                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm deleteBtn mt-1">
                                <i class="fas fa-trash"></i>
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

        <div class="card-footer text-center">
          <div class="d-inline-block mt-3">
            {{ $products->appends(['language' => request()->input('language')])->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
