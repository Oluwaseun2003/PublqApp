@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Registered Customers') }}</h4>
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
        <a href="#">{{ __('Customers Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Registered Customers') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('All Customers') }}</div>
            </div>

            <div class="col-lg-6 offset-lg-2">
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete mr-2 ml-3 mt-1"
                data-href="{{ route('admin.customer_management.bulk_delete_customer') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>

              <form class="float-right" action="" method="GET">
                <input name="info" type="text" class="form-control min-230"
                  placeholder="Search By Username or Email ID"
                  value="{{ !empty(request()->input('info')) ? request()->input('info') : '' }}">
              </form>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($customers) == 0)
                <h3 class="text-center">{{ __('NO CUSTOMER FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Email ') }}</th>
                        <th scope="col">{{ __('Phone') }}</th>
                        <th scope="col">{{ __('Account Status') }}</th>
                        <th scope="col">{{ __('Email Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($customers as $customer)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $customer->id }}">
                          </td>
                          <td>{{ $customer->fname }} {{ $customer->lname }}</td>
                          <td>{{ $customer->email }}</td>
                          <td>{{ empty($customer->phone) ? '-' : $customer->phone }}</td>
                          <td>
                            <form id="accountStatusForm-{{ $customer->id }}" class="d-inline-block"
                              action="{{ route('admin.customer_management.customer.update_account_status', ['id' => $customer->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $customer->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="account_status"
                                onchange="document.getElementById('accountStatusForm-{{ $customer->id }}').submit()">
                                <option value="1" {{ $customer->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $customer->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <form id="emailStatusForm-{{ $customer->id }}" class="d-inline-block"
                              action="{{ route('admin.customer_management.customer.update_email_status', ['id' => $customer->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ !is_null($customer->email_verified_at) ? 'bg-success' : 'bg-danger' }}"
                                name="email_status"
                                onchange="document.getElementById('emailStatusForm-{{ $customer->id }}').submit()">
                                <option value="1" {{ !is_null($customer->email_verified_at) ? 'selected' : '' }}>
                                  {{ __('Verified') }}
                                </option>
                                <option value="0" {{ is_null($customer->email_verified_at) ? 'selected' : '' }}>
                                  {{ __('Not Verified') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="{{ route('admin.customer_management.customer_details', ['id' => $customer->id, 'language' => $defaultLang->code]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>


                                <a href="{{ route('admin.customer_management.customer_edit', ['id' => $customer->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>

                                <a href="{{ route('admin.customer_management.customer.change_password', ['id' => $customer->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Change Password') }}
                                </a>

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.customer_management.customer_delete', ['id' => $customer->id]) }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>

                                <a target="_blank"
                                  href="{{ route('admin.customer_management.secret_login', ['id' => $customer->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Secret Login') }}
                                </a>
                              </div>
                            </div>
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
            {{ $customers->appends(['info' => request()->input('info')])->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
