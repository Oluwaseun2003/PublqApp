@extends('backend.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Permissions') }}</h4>
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
        <a href="#">{{ __('Admin Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Role') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Permissions') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('admin.admin_management.role.update_permissions', ['id' => $role->id]) }}" method="post">
          @csrf
          <div class="card-header">
            <div class="card-title d-inline-block">{{ __('Permissions of') . ' ' . $role->name }}</div>
            <a class="btn btn-info btn-sm float-right d-inline-block"
              href="{{ route('admin.admin_management.role_permissions') }}">
              <span class="btn-label">
                <i class="fas fa-backward"></i>
              </span>
              {{ __('Back') }}
            </a>
          </div>

          <div class="card-body py-5">
            <div class="row justify-content-center">
              <div class="col-lg-5">
                <div class="alert alert-warning text-center" role="alert">
                  <strong class="text-dark">{{ __('Select from this below options') }}</strong>
                </div>
              </div>
            </div>

            @php $rolePermissions = json_decode($role->permissions); @endphp

            <div class="row mt-3 justify-content-center">
              <div class="col-lg-10">
                <div class="form-group">
                  <div class="selectgroup selectgroup-pills">
                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Admin Management"
                        @if (is_array($rolePermissions) && in_array('Admin Management', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Admin Management') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Language Management"
                        @if (is_array($rolePermissions) && in_array('Language Management', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Language Management') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="PWA Settings"
                        @if (is_array($rolePermissions) && in_array('PWA Settings', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('PWA Settings') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Basic Settings"
                        @if (is_array($rolePermissions) && in_array('Basic Settings', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Basic Settings') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Payment Gateways"
                        @if (is_array($rolePermissions) && in_array('Payment Gateways', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Payment Gateways') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Push Notification"
                        @if (is_array($rolePermissions) && in_array('Push Notification', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Push Notification') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Subscribers"
                        @if (is_array($rolePermissions) && in_array('Subscribers', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Subscribers') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Announcement Popups"
                        @if (is_array($rolePermissions) && in_array('Announcement Popups', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Announcement Popups') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Advertise"
                        @if (is_array($rolePermissions) && in_array('Advertise', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Advertise') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Contact Page"
                        @if (is_array($rolePermissions) && in_array('Contact Page', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Contact Page') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="FAQ Management"
                        @if (is_array($rolePermissions) && in_array('FAQ Management', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('FAQ Management') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Blog Management"
                        @if (is_array($rolePermissions) && in_array('Blog Management', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Blog Management') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Custom Pages"
                        @if (is_array($rolePermissions) && in_array('Custom Pages', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Custom Pages') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Footer"
                        @if (is_array($rolePermissions) && in_array('Footer', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Footer') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Home Page"
                        @if (is_array($rolePermissions) && in_array('Home Page', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Home Page') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Shop Management"
                        @if (is_array($rolePermissions) && in_array('Shop Management', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Shop Management') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Support Ticket"
                        @if (is_array($rolePermissions) && in_array('Support Ticket', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Support Ticket') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]"
                        value="Customer Management" @if (is_array($rolePermissions) && in_array('Customer Management', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Customer Management') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]"
                        value="Organizer Mangement" @if (is_array($rolePermissions) && in_array('Organizer Mangement', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Organizer Mangement') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Transaction"
                        @if (is_array($rolePermissions) && in_array('Transaction', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Transaction') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Event Management"
                        @if (is_array($rolePermissions) && in_array('Event Management', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Event Management') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Event Bookings"
                        @if (is_array($rolePermissions) && in_array('Event Bookings', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Event Bookings') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Withdraw Method"
                        @if (is_array($rolePermissions) && in_array('Withdraw Method', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Withdraw Method') }}</span>
                    </label>
                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Menu Builder"
                        @if (is_array($rolePermissions) && in_array('Menu Builder', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Menu Builder') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Lifetime Earning"
                        @if (is_array($rolePermissions) && in_array('Lifetime Earning', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Lifetime Earning') }}</span>
                    </label>

                    <label class="selectgroup-item">
                      <input type="checkbox" class="selectgroup-input" name="permissions[]" value="Total Profit"
                        @if (is_array($rolePermissions) && in_array('Total Profit', $rolePermissions)) checked @endif>
                      <span class="selectgroup-button">{{ __('Total Profit') }}</span>
                    </label>

                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="row">
              <div class="col-12 text-center">
                <button type="submit" class="btn btn-success">
                  {{ __('Update') }}
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
