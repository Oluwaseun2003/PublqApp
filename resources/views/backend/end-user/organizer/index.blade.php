@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Registered Organizers') }}</h4>
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
        <a href="#">{{ __('Organizers Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Registered Organizers') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('All Organizers') }}</div>
            </div>

            <div class="col-lg-6 offset-lg-2">
              <button class="btn btn-danger btn-sm float-right d-none bulk-delete mr-2 ml-3 mt-1"
                data-href="{{ route('admin.organizer_management.bulk_delete_organizer') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>

              <form class="float-right" action="{{ route('admin.organizer_management.registered_organizer') }}"
                method="GET">
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
              @if (count($organizers) == 0)
                <h3 class="text-center">{{ __('NO ORGANIZER FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email ID') }}</th>
                        <th scope="col">{{ __('Phone') }}</th>
                        <th scope="col">{{ __('Account Status') }}</th>
                        <th scope="col">{{ __('Email Status') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($organizers as $organizer)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $organizer->id }}">
                          </td>
                          <td>{{ $organizer->username }}</td>
                          <td>{{ $organizer->email }}</td>
                          <td>{{ empty($organizer->phone) ? '-' : $organizer->phone }}</td>
                          <td>
                            <form id="accountStatusForm-{{ $organizer->id }}" class="d-inline-block"
                              action="{{ route('admin.organizer_management.organizer.update_account_status', ['id' => $organizer->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ $organizer->status == 1 ? 'bg-success' : 'bg-danger' }}"
                                name="account_status"
                                onchange="document.getElementById('accountStatusForm-{{ $organizer->id }}').submit()">
                                <option value="1" {{ $organizer->status == 1 ? 'selected' : '' }}>
                                  {{ __('Active') }}
                                </option>
                                <option value="0" {{ $organizer->status == 0 ? 'selected' : '' }}>
                                  {{ __('Deactive') }}
                                </option>
                              </select>
                            </form>
                          </td>
                          <td>
                            <form id="emailStatusForm-{{ $organizer->id }}" class="d-inline-block"
                              action="{{ route('admin.organizer_management.organizer.update_email_status', ['id' => $organizer->id]) }}"
                              method="post">
                              @csrf
                              <select
                                class="form-control form-control-sm {{ !is_null($organizer->email_verified_at) ? 'bg-success' : 'bg-danger' }}"
                                name="email_status"
                                onchange="document.getElementById('emailStatusForm-{{ $organizer->id }}').submit()">
                                <option value="1" {{ !is_null($organizer->email_verified_at) ? 'selected' : '' }}>
                                  {{ __('Verified') }}
                                </option>
                                <option value="0" {{ is_null($organizer->email_verified_at) ? 'selected' : '' }}>
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
                                <a href="{{ route('admin.organizer_management.organizer_details', ['id' => $organizer->id, 'language' => $defaultLang->code]) }}"
                                  class="dropdown-item">
                                  {{ __('Details') }}
                                </a>

                                <a href="{{ route('admin.edit_management.organizer_edit', ['id' => $organizer->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Edit') }}
                                </a>

                                <a href="{{ route('admin.organizer_management.organizer.change_password', ['id' => $organizer->id]) }}"
                                  class="dropdown-item">
                                  {{ __('Change Password') }}
                                </a>

                                <form class="deleteForm d-block"
                                  action="{{ route('admin.organizer_management.organizer.delete', ['id' => $organizer->id]) }}"
                                  method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>

                                <a target="_blank"
                                  href="{{ route('admin.organizer_management.organizer.secret_login', ['id' => $organizer->id]) }}"
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
            {{ $organizers->appends(['info' => request()->input('info')])->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
