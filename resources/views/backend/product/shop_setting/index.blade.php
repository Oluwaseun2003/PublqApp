@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Shipping Charge') }}</h4>
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
        <a href="">{{ __('Shop Management') }}</a>
      </li>

      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Shipping Charges') }}</a>
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
                {{ __('Shipping Charge')  }}
              </div>
            </div>

            <div class="col-lg-3">
              @includeIf('backend.partials.languages')
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">

                <a href=""  data-toggle="modal" data-target="#createModal" class="btn btn-secondary btn-sm float-right" ><i class="fas fa-plus-circle"></i> {{ __('Add New') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete" data-href="{{ route('admin.shop_management.bulk_delete_shipping_charge') }}">
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

              @if (count($collection) == 0)
                <h3 class="text-center mt-2">{{ __('NO SHIPPING CHARGES ARE FOUND'). '!' }}</h3>
              @else

                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Text') }}</th>
                        <th scope="col">{{ __('Charge') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($collection as $item)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $item->id }}">
                          </td>
                          <td>
                            {{$item->title}}
                          </td>
                          <td>
                            {{$item->text}}
                          </td>
                          <td>
                            {{$item->charge}}
                          </td>
                          <td>
                            <a class="btn btn-secondary mt-1 btn-xs mr-1 editBtn" href="#" data-toggle="modal" data-target="#editshippingCharge" data-id="{{ $item->id }}" data-title="{{ $item->title }}" data-language_id="{{ $item->language_id }}" data-text="{{ $item->text }}" data-charge="{{ $item->charge }}" >
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block" action="{{ route('admin.shop_management.delete_shipping', ['id' => $item->id]) }}" method="post">

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
  {{-- --create modal --}}
  @include('backend.product.shop_setting.create')
  {{-- edit modal --}}
  @include('backend.product.shop_setting.edit')
@endsection
