@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Support Tickets') }}</h4>
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
        <a href="#">{{ __('Support Tickets') }}</a>
      </li>
      @if (!request()->filled('status'))
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('All Tickets') }}</a>
        </li>
      @endif
      @if (request()->filled('status') && request()->input('status') == 1)
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Pending Tickets') }}</a>
        </li>
      @endif
      @if (request()->filled('status') && request()->input('status') == 2)
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Open Tickets') }}</a>
        </li>
      @endif
      @if (request()->filled('status') && request()->input('status') == 3)
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Closed Tickets') }}</a>
        </li>
      @endif
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">


          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">
                {{ __('Support Tickets') }}
              </div>
            </div>

            <div class="col-lg-8 mt-2 mt-lg-0">
              <button class="btn btn-danger float-lg-right float-none btn-sm ml-2 mt-1 bulk-delete d-none"
                data-href="{{ route('admin.support_tickets.bulk_delete') }}"><i class="flaticon-interface-5"></i>
                {{ __('Delete') }}</button>

              <form action="" class="float-lg-right float-none" method="GET">
                <input type="text" name="ticket_id" class="form-control min-w-250" placeholder="Search by Ticket ID"
                  value="{{ !empty(request()->input('ticket_id')) ? request()->input('ticket_id') : '' }}">
              </form>
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
                <h3 class="text-center mt-2">{{ __('NO SUPPORT TICKETS FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Ticket ID') }}</th>
                        <th scope="col">{{ __('User Type') }}</th>
                        <th scope="col">{{ __('Username') }}</th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Subject') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Action') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($collection as $item)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $item->id }}">
                          </td>
                          <td>
                            {{ $item->id }}
                          </td>
                          <td>
                            <span
                              class="badge badge-{{ $item->user_type == 'customer' ? 'info' : 'success' }}">{{ ucfirst($item->user_type) }}</span>
                          </td>
                          <td>
                            @if ($item->user_type == 'organizer')
                              @if ($item->organizer)
                                <a
                                  href="{{ route('admin.organizer_management.organizer_details', ['id' => @$item->organizer->id, 'language' => $defaultLang->code]) }}">{{ @$item->organizer->username }}</a>
                              @else
                                -
                              @endif
                            @else
                              @if ($item->customer)
                                <a
                                  href="{{ route('admin.customer_management.customer_details', ['id' => @$item->customer->id, 'language' => $defaultLang->code]) }}">{{ @$item->customer->username }}</a>
                              @else
                                -
                              @endif
                            @endif
                          </td>
                          <td>
                            {{ $item->email != '' ? $item->email : '-' }}
                          </td>
                          <td>
                            {{ $item->subject }}
                          </td>
                          <td>
                            @if ($item->status == 1)
                              <span class="badge badge-info">{{ __('Pending') }}</span>
                            @elseif($item->status == 2)
                              <span class="badge badge-success">{{ __('Open') }}</span>
                            @elseif($item->status == 3)
                              <span class="badge badge-danger">{{ __('Closed') }}</span>
                            @endif
                          </td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-secondary dropdown-toggle btn-sm" type="button"
                                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ __('Select') }}
                              </button>

                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @if (empty(Auth::guard('admin')->user()->role_id))
                                  @if (!empty($item->admin_id))
                                    <a href="{{ route('admin.support_tickets.unassign', $item->id) }}"
                                      class="dropdown-item">
                                      {{ __('Unassign') }}
                                    </a>
                                  @else
                                    <a href="javascript:void(0)" data-toggle="modal"
                                      data-target="#exampleModal{{ $item->id }}" class="dropdown-item">
                                      {{ __('Assing To') }}
                                    </a>
                                  @endif
                                @endif
                                <a href="{{ route('admin.support_tickets.message', $item->id) }}" class="dropdown-item">
                                  {{ __('Message') }}
                                </a>
                                <form class="deleteForm d-block"
                                  action="{{ route('admin.support_tickets.delete', $item->id) }}" method="post">
                                  @csrf
                                  <button type="submit" class="deleteBtn">
                                    {{ __('Delete') }}
                                  </button>
                                </form>
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

        <div class="card-footer">
          {{ $collection->links() }}
        </div>
      </div>
    </div>
  </div>


  @foreach ($collection as $item)
    <!-- Modal -->
    <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ __('Assign Staff') }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{ route('assign_stuff.supoort.ticket', $item->id) }}" method="post">
            @csrf
            <div class="modal-body">
              @csrf
              <div class="form-group">
                @php
                  $admins = App\Models\Admin::where('role_id', '!=', null)->get();
                @endphp
                <label for="">{{ __('Staff') }} *</label>
                <select name="admin_id" id="" class="form-control">
                  @foreach ($admins as $admin)
                    <option value="{{ $admin->id }}">{{ $admin->first_name }} {{ $admin->last_name }}</option>
                  @endforeach

                </select>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
              <button type="submit" class="btn btn-primary">{{ __('Assign') }}</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endforeach
@endsection
