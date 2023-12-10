<div class="col-lg-3">
  <div class="user-sidebar">
    <ul class="links">
      <li><a href="{{ route('customer.dashboard') }}"
          class="@if (request()->routeIs('customer.dashboard')) active @endif">{{ __('Dashboard') }}</a></li>
      <li><a href="{{ route('customer.booking.my_booking') }}" class="@if (request()->routeIs('customer.booking.my_booking') || request()->routeIs('customer.booking_details')) active @endif">
          {{ __('Event Bookings') }} </a></li>
      <li><a href="{{ route('customer.my_orders') }}"
          class="
            @if (request()->routeIs('customer.my_orders')) active
            @elseif (request()->routeIs('customer.order_details')) active @endif
            ">
          {{ __('Product Orders') }} </a></li>

      <li><a href="{{ route('customer.wishlist') }}"
          class="@if (request()->routeIs('customer.wishlist')) active @endif">{{ __('Wishlist') }}</a></li>

      <li><a href="{{ route('customer.support_tickert') }}"
          class="@if (request()->routeIs('customer.support_tickert')) active
            @elseif(request()->routeIs('customer.support_tickert.create')) active
            @elseif(request()->routeIs('customer.support_ticket.message')) active @endif">
          {{ __('Support Tickets') }}</a></li>
      <li><a href="{{ route('customer.edit.profile') }}"
          class="@if (request()->routeIs('customer.edit.profile')) active @endif">{{ __('Edit Profile') }} </a></li>
      <li><a href="{{ route('customer.change.password') }}"
          class="@if (request()->routeIs('customer.change.password')) active @endif">{{ __('Change Password') }}</a></li>

      <li><a href="{{ route('customer.logout') }}" class="">{{ __('Logout') }} </a></li>
    </ul>
  </div>
</div>
