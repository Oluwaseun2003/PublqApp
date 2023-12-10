@extends('backend.layout')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Conversations') }}</h4>
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
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('organizer.support_tickets') }}">{{ __('All Tickets') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Conversations') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Ticket Details') }} - #{{ $ticket->id }}</div>
            </div>
            <div class="col-lg-3 offset-lg-5 mt-2 mt-lg-0 text-left text-lg-right">
              <a href="{{ route('admin.support_tickets') }}" class="btn btn-primary btn-md">{{ __('Back to Lists') }}</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-lg-12">
              <div class="row">
                <div class="col-lg-12">
                  <h3 class="text-white">{{ $ticket->subject }}</h3>

                  @if ($ticket->status != 3)
                    <form class="closeForm d-block" action="{{ route('admin.support_ticket.close', $ticket->id) }}"
                      method="post">

                      @csrf
                      <button class="close-ticket btn btn-success btn-md TicketCloseBtn" data-href=""><i
                          class="fas fa-check mr-1"></i> {{ __('Close Ticket') }}</button>
                    </form>
                  @endif
                </div>
                <div class="col-lg-12 my-3">
                  @if ($ticket->status == 1)
                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                  @elseif($ticket->status == 2)
                    <span class="badge badge-primary">{{ __('Open') }}</span>
                  @else
                    <span class="badge badge-danger">{{ __('Closed') }}</span>
                  @endif
                  <span class="badge badge-secondary">{{ $ticket->created_at->format('d-m-Y') }}
                    {{ date('h.i A', strtotime($ticket->created_at)) }}</span>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-8 offset-lg-2">
                  <p class="font-16">{{ $ticket->description }}</p>
                  @if ($ticket->attachment)
                    @if ($ticket->user_type == 'customer')
                      <a href="{{ asset('assets/admin/img/support-ticket/' . $ticket->attachment) }}"
                        download="{{ __('support_file') }}" class="btn btn-primary"><i class="fas fa-download"></i>
                        {{ __('Download Attachment') }}</a>
                    @else
                      <a href="{{ asset('assets/admin/img/support-ticket/attachment/' . $ticket->attachment) }}"
                        download="{{ __('support_file') }}" class="btn btn-primary"><i class="fas fa-download"></i>
                        {{ __('Download Attachment') }}</a>
                    @endif
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="{{ $ticket->status == 3 ? 'col-lg-12' : 'col-lg-6' }}">
      <div class="card card-round">
        <div class="card-body">
          <div class="card-title fw-mediumbold">{{ __('Replies') }}</div>
          <div class="card-list">
            <div class="messages-container">
              @if (count($ticket->messages) > 0)
                @foreach ($ticket->messages as $reply)
                  @if ($reply->type == 2)
                    @php
                      $admin = App\Models\Admin::where('id', $reply->user_id)->first();
                    @endphp
                    <div class="item-list">
                      <div class="avatar">
                        <img
                          src="{{ $admin ? asset('assets/admin/img/admins/' . $admin->image) : asset('assets/admin/img/propics/blank_user.jpg') }}"
                          alt="..." class="avatar-img rounded-circle">
                      </div>
                      <div class="info-user ml-3">
                        <div class="username">{{ $admin->username }}</div>
                        <div class="status">{{ $admin->id == 1 ? __('Super Admin') : $admin->role->name }}</div>
                        <p>{!! $reply->reply !!}</p>
                        @if ($reply->file)
                          <a href="{{ asset('assets/admin/img/support-ticket/' . $reply->file) }}"
                            download="support_file" class="btn btn-rounded btn-info btn-sm">{{ __('Download') }}</a>
                        @endif
                      </div>
                    </div>
                  @else
                    @if ($reply->type == 1)
                      @php
                        $user = App\Models\Customer::where('id', $ticket->user_id)->first();
                      @endphp
                      <div class="item-list">
                        <div class="avatar">
                          @if ($user->photo)
                            <img class="avatar-img rounded-circle"
                              src="{{ asset('assets/admin/img/customer-profile/' . $user->photo) }}" alt="user-photo">
                          @else
                            <img class="avatar-img rounded-circle" src="{{ asset('assets/front/img/user/profile.jpg') }}"
                              alt="user-photo">
                          @endif
                        </div>
                        <div class="info-user ml-3">
                          <div class="username">{{ $user->fname }} {{ $user->lname }}</div>
                          <div class="status">{{ __('Customer') }}</div>
                          <p>{!! $reply->reply !!}</p>
                          @if ($reply->file)
                            <a href="{{ asset('assets/admin/img/support-ticket/' . $reply->file) }}"
                              download="support_file" class="btn btn-rounded btn-info btn-sm">{{ __('Download') }}</a>
                          @endif
                        </div>
                      </div>
                    @elseif ($reply->type == 3)
                      @php
                        $organizer = App\Models\Organizer::where('id', $ticket->user_id)->first();
                      @endphp
                      <div class="item-list">
                        <div class="avatar">
                          @if ($organizer)
                            @if ($organizer->photo)
                              <img class="avatar-img rounded-circle"
                                src="{{ asset('assets/admin/img/organizer-photo/' . $organizer->photo) }}"
                                alt="user-photo">
                            @else
                              <img class="avatar-img rounded-circle" src="{{ asset('assets/front/images/profile.jpg') }}"
                                alt="user-photo">
                            @endif
                          @else
                            <img class="avatar-img rounded-circle" src="{{ asset('assets/front/images/profile.jpg') }}"
                              alt="user-photo">
                          @endif


                        </div>
                        <div class="info-user ml-3">
                          <div class="username">{{ @$organizer->username }}</div>
                          <div class="status">{{ __('Organizer') }}</div>
                          <p>{!! $reply->reply !!}</p>
                          @if ($reply->file)
                            <a href="{{ asset('assets/admin/img/support-ticket/' . $reply->file) }}"
                              download="support_file" class="btn btn-rounded btn-info btn-sm">{{ __('Download') }}</a>
                          @endif
                        </div>
                      </div>
                    @endif
                  @endif
                @endforeach
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>


    @if ($ticket->status != 3)
      <div class="col-lg-6 message-type">
        <div class="card card-round">
          <div class="card-body">
            <div class="card-title fw-mediumbold mb-2">{{ __('Reply to Ticket') }}</div>
            <form action="{{ route('admin.support_ticket.reply', $ticket->id) }}" id="ajaxform" method="POST"
              enctype="multipart/form-data">@csrf
              <div class="form-group">
                <label for="">{{ __('Message') . ' **' }} </label>
                <textarea name="reply" class="summernote" data-height="200"></textarea>
                <p class="em text-danger mb-0" id="errreply"></p>
              </div>
              <div class="form-group">
                <label for="">{{ __('Attachment') }}</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input"
                      data-href="{{ route('admin.support_ticket.zip_file.upload') }}" name="file" id="zip_file"
                      accept=".zip">
                    <label class="custom-file-label" for="zip_file">{{ __('Choose file') }}</label>
                  </div>
                </div>
                <p class="em text-danger mb-0" id="errfile"></p>
                <p class="mb-0 show-name"><small></small></p>
                <div class="progress progress-sm d-none">
                  <div class="progress-bar bg-success " role="progressbar" aria-valuenow="" aria-valuemin="0"
                    aria-valuemax=""></div>
                </div>
                <p class="text-warning">{{ __('Upload only ZIP Files, Max File Size is 5 MB') }}</p>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-success">{{ __('Send') }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection
@section('script')
  <script src="{{ asset('assets/admin/js/support.js') }}"></script>
@endsection
