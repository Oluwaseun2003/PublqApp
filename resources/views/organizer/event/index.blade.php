@extends('organizer.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Events') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('organizer.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Event Management') }}</a>
            </li>
            @if (!request()->filled('event_type'))
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a
                        href="{{ route('admin.event_management.event', ['language' => $defaultLang->code]) }}">{{ __('All Events') }}</a>
                </li>
            @endif
            @if (request()->filled('event_type') && request()->input('event_type') == 'venue')
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a
                        href="{{ route('organizer.event_management.event', ['language' => $defaultLang->code, 'event_type' => 'venue']) }}">{{ __('Venue Events') }}</a>
                </li>
            @endif
            @if (request()->filled('event_type') && request()->input('event_type') == 'online')
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">{{ __('Online Events') }}</a>
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
                                {{ __('Events') . ' (' . $language->name . ' ' . __('Language') . ')' }}
                            </div>
                        </div>

                        <div class="col-lg-3">
                            @if (!empty($langs))
                                <select name="language" class="form-control"
                                    onchange="window.location='{{ url()->current() . '?language=' }}' + this.value+'&event_type='+'{{ request()->input('event_type') }}'">
                                    <option selected disabled>{{ __('Select a Language') }}</option>
                                    @foreach ($langs as $lang)
                                        <option value="{{ $lang->code }}"
                                            {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                                            {{ $lang->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">

                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle btn-sm float-right" type="button"
                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    {{ __('Add Event') }}
                                </button>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a href="{{ route('organizer.add.event.event', ['type' => 'online']) }}"
                                        class="dropdown-item">
                                        {{ __('Online Event') }}
                                    </a>

                                    <a href="{{ route('organizer.add.event.event', ['type' => 'venue']) }}"
                                        class="dropdown-item">
                                        {{ __('Venue Event') }}
                                    </a>
                                </div>
                            </div>

                            <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                                data-href="{{ route('organizer.event_management.bulk_delete_event') }}">
                                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="float-right">
                                <div class="form-group">
                                    <form action="" method="get">
                                        <input type="hidden" name="language" value="{{ request()->input('language') }}"
                                            class="hidden">
                                        <input type="text" name="title" value="{{ request()->input('title') }}"
                                            name="name" placeholder="Enter Event Name" class="form-control">
                                    </form>
                                </div>
                            </div>

                            @if (count($events) == 0)
                                <h3 class="text-center mt-2">
                                    {{ __('NO EVENT CONTENT FOUND FOR ') . $language->name . '!' }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col" width="30%">{{ __('Title') }}</th>
                                                <th scope="col">{{ __('Type') }}</th>
                                                <th scope="col">{{ __('Category') }}</th>
                                                <th scope="col">{{ __('Ticket') }}</th>
                                                <th scope="col">{{ __('Status') }}</th>
                                                <th scope="col">{{ __('Featured') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($events as $event)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $event->id }}">
                                                    </td>
                                                    <td width="20%">
                                                        <a target="_blank"
                                                            href="{{ route('event.details', ['slug' => $event->slug, 'id' => $event->id]) }}">{{ strlen($event->title) > 30 ? mb_substr($event->title, 0, 30, 'UTF-8') . '....' : $event->title }}</a>
                                                    </td>
                                                    <td>
                                                        {{ ucfirst($event->event_type) }}
                                                    </td>
                                                    <td>
                                                        {{ $event->category }}
                                                    </td>
                                                    <td>
                                                        @if ($event->event_type == 'venue')
                                                            <a href="{{ route('organizer.event.ticket', ['language' => request()->input('language'), 'event_id' => $event->id, 'event_type' => $event->event_type]) }}"
                                                                class="btn btn-success btn-sm">{{ __('Manage') }}</a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form id="statusForm-{{ $event->id }}" class="d-inline-block"
                                                            action="{{ route('organizer.event_management.event.event_status', ['id' => $event->id, 'language' => request()->input('language')]) }}"
                                                            method="post">

                                                            @csrf
                                                            <select
                                                                class="form-control form-control-sm {{ $event->status == 0 ? 'bg-warning text-dark' : 'bg-primary' }}"
                                                                name="status"
                                                                onchange="document.getElementById('statusForm-{{ $event->id }}').submit()">
                                                                <option value="1"
                                                                    {{ $event->status == 1 ? 'selected' : '' }}>
                                                                    {{ __('Active') }}
                                                                </option>
                                                                <option value="0"
                                                                    {{ $event->status == 0 ? 'selected' : '' }}>
                                                                    {{ __('Deactive') }}
                                                                </option>
                                                            </select>
                                                        </form>
                                                    </td>
                                                    <td>

                                                        <form id="featuredForm-{{ $event->id }}"
                                                            class="d-inline-block"
                                                            action="{{ route('organizer.event_management.event.update_featured', ['id' => $event->id]) }}"
                                                            method="post">

                                                            @csrf
                                                            <select
                                                                class="form-control form-control-sm {{ $event->is_featured == 'yes' ? 'bg-success' : 'bg-danger' }}"
                                                                name="is_featured"
                                                                onchange="document.getElementById('featuredForm-{{ $event->id }}').submit()">
                                                                <option value="yes"
                                                                    {{ $event->is_featured == 'yes' ? 'selected' : '' }}>
                                                                    {{ __('Yes') }}
                                                                </option>
                                                                <option value="no"
                                                                    {{ $event->is_featured == 'no' ? 'selected' : '' }}>
                                                                    {{ __('No') }}
                                                                </option>
                                                            </select>
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary dropdown-toggle btn-sm"
                                                                type="button" id="dropdownMenuButton"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                {{ __('Select') }}
                                                            </button>

                                                            <div class="dropdown-menu"
                                                                aria-labelledby="dropdownMenuButton">
                                                                <a href="{{ route('organizer.event_management.edit_event', ['id' => $event->id]) }}"
                                                                    class="dropdown-item">
                                                                    {{ __('Edit') }}
                                                                </a>

                                                                <form class="deleteForm d-block"
                                                                    action="{{ route('organizer.event_management.delete_event', ['id' => $event->id]) }}"
                                                                    method="post">

                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm deleteBtn">
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

                <div class="card-footer text-center">
                    <div class="d-inline-block mt-3">
                        {{ $events->appends([
                                'language' => request()->input('language'),
                                'title' => request()->input('title'),
                            ])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
